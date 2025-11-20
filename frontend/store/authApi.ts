// file: frontend/store/authApi.ts
import {
  createApi,
  fetchBaseQuery,
  BaseQueryFn,
  FetchArgs,
  FetchBaseQueryError,
} from "@reduxjs/toolkit/query/react";
import { setCredentials, logOut } from "./authSlice";
import type { RootState } from "./store";

interface LoginResponse {
  success: boolean;
  message: string;
  token: string;
}

interface RegisterResponse {
  success: boolean;
  message: string;
}

interface RefreshResponse {
  success: boolean;
  token: string;
}

// Standard base query with Access Token header
const RbQuery = fetchBaseQuery({
  baseUrl: "/",
  prepareHeaders: (headers, { getState }) => {
    const token = (getState() as RootState).auth.token;
    if (token) {
      headers.set("Authorization", `Bearer ${token}`);
    }
    return headers;
  },
});

// Wrapper to handle Token Expiration (401)
const baseQueryWithReauth: BaseQueryFn<
  string | FetchArgs,
  unknown,
  FetchBaseQueryError
> = async (args, api, extraOptions) => {
  let result = await RbQuery(args, api, extraOptions);

  // If the backend says "401 Unauthorized" (Access Token expired)
  if (result.error && result.error.status === 401) {
    // Avoid infinite loops
    const url = typeof args === "string" ? args : args.url;
    if (url === "/api/refresh") {
      api.dispatch(logOut());
      return result;
    }

    console.log("Access token expired. Attempting to refresh via Cookie...");

    // Call /api/refresh.
    // IMPORTANT: The browser automatically sends the HttpOnly cookie here.
    const refreshResult = await RbQuery(
      { url: "/api/refresh", method: "POST" },
      api,
      extraOptions
    );

    if (refreshResult.data) {
      // If successful, we get a new Access Token
      const refreshData = refreshResult.data as RefreshResponse;
      const currentUser = (api.getState() as RootState).auth.user;

      // Save new token to Redux + LocalStorage
      api.dispatch(
        setCredentials({
          token: refreshData.token,
          user: currentUser || "",
        })
      );

      // Retry the original failed request
      result = await RbQuery(args, api, extraOptions);
    } else {
      // If refresh fails (cookie expired), log out
      api.dispatch(logOut());
    }
  }

  return result;
};

export const authApi = createApi({
  reducerPath: "authApi",
  baseQuery: baseQueryWithReauth,
  endpoints: (builder) => ({
    login: builder.mutation<LoginResponse, any>({
      query: (credentials) => ({
        url: "/api/login",
        method: "POST",
        body: credentials,
      }),
      async onQueryStarted(arg, { dispatch, queryFulfilled }) {
        try {
          const { data } = await queryFulfilled;
          // Save token to store (which also saves to LocalStorage via slice)
          dispatch(setCredentials({ token: data.token, user: arg.name }));
        } catch (error) {}
      },
    }),
    register: builder.mutation<RegisterResponse, any>({
      query: (credentials) => ({
        url: "/api/register",
        method: "POST",
        body: credentials,
      }),
    }),
    logout: builder.mutation<void, void>({
      query: () => ({
        url: "/api/logout",
        method: "POST",
      }),
      async onQueryStarted(arg, { dispatch, queryFulfilled }) {
        // Clear local state immediately
        dispatch(logOut());
      },
    }),
  }),
});

export const { useLoginMutation, useRegisterMutation, useLogoutMutation } =
  authApi;
