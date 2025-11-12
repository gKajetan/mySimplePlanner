"use client";

import React, { useState, FormEvent } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { useLoginMutation, useRegisterMutation } from "../store/authApi";
import { Loader2 } from "lucide-react";

// Real shadcn/ui component imports
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

type AuthMode = "login" | "register";

export default function AuthForm({ mode }: { mode: AuthMode }) {
  const [name, setName] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);

  const router = useRouter();
  const [login, { isLoading: isLoggingIn }] = useLoginMutation();
  const [register, { isLoading: isRegistering }] = useRegisterMutation();
  const isLoading = isLoggingIn || isRegistering;

  const title = mode === "login" ? "Sign In" : "Create an Account";
  const desc =
    mode === "login"
      ? "Enter your credentials to access your dashboard."
      : "Enter a username and password to get started.";

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);

    try {
      if (mode === "login") {
        const data = await login({ name, password }).unwrap();
        if (data.success) {
          router.push("/dashboard"); // Redirect to dashboard
        } else {
          setError(data.message || "Invalid name or password");
        }
      } else {
        const data = await register({ name, password }).unwrap();
        if (data.success) {
          setSuccess(data.message + " You can now sign in.");
        } else {
          setError(data.message || "Registration failed.");
        }
      }
    } catch (err: any) {
      setError(
        err.data?.message || err.message || "An unexpected error occurred."
      );
    }
  };

  return (
    <div className="flex min-h-screen w-full items-center justify-center bg-neutral-100 dark:bg-black p-4">
      <Card className="w-full max-w-md">
        <CardHeader className="text-center">
          <CardTitle className="text-2xl">{title}</CardTitle>
          <CardDescription>{desc}</CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-4">
            {error && (
              <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm text-center">
                {error}
              </div>
            )}
            {success && (
              <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm text-center">
                {success}
              </div>
            )}
            <div className="space-y-2">
              <Label htmlFor="name">Username</Label>
              <Input
                id="name"
                name="name"
                type="text"
                required
                autoComplete="username"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="test"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="password">Password</Label>
              <Input
                id="password"
                name="password"
                type="password"
                required
                autoComplete="current-password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="test"
              />
            </div>
            <Button type="submit" disabled={isLoading} className="w-full">
              {isLoading ? (
                <Loader2 className="mr-2 h-4 w-4 animate-spin" />
              ) : (
                title
              )}
            </Button>
          </form>
        </CardContent>
        <CardFooter>
          <p className="w-full text-center text-sm text-neutral-600 dark:text-neutral-400">
            {mode === "login"
              ? "Don't have an account?"
              : "Already have an account?"}{" "}
            <Link
              href={mode === "login" ? "/register" : "/login"}
              className="font-medium text-blue-600 hover:text-blue-500"
            >
              {mode === "login" ? "Sign Up" : "Sign In"}
            </Link>
          </p>
        </CardFooter>
      </Card>
    </div>
  );
}
