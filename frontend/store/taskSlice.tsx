import { createSlice, PayloadAction } from "@reduxjs/toolkit";
import { Task } from "@/lib/types";

// --- Mock Data (Polski) ---
const mockTasks: Task[] = [
  {
    id: "2025-11-12T18:00:00.000Z",
    topic: "Projekt Feniks",
    title: "Zaprojektować stronę główną",
    description: "Stworzyć makiety dla sekcji hero i stopki.",
    importance: 5,
    createdAt: "2025-11-12T18:00:00.000Z",
  },
  {
    id: "2025-11-11T14:30:00.000Z",
    topic: "Sprawy domowe",
    title: "Zrobić zakupy",
    description: "Mleko, chleb, jajka",
    importance: 2,
    createdAt: "2025-11-11T14:30:00.000Z",
  },
  {
    id: "2025-11-12T10:15:00.000Z",
    topic: "Projekt Feniks",
    title: "Skonfigurować API",
    description: "Ustawić endpointy logowania i rejestracji.",
    importance: 4,
    createdAt: "2025-11-12T10:15:00.000Z",
  },
  {
    id: "2025-11-10T09:00:00.000Z",
    topic: "Nauka",
    title: "Przeczytać dokumentację React",
    description: "Skupić się na hookach i Redux.",
    importance: 3,
    createdAt: "2025-11-10T09:00:00.000Z",
  },
];
// --- End of Mock Data ---

interface TaskState {
  tasks: Task[];
}

const initialState: TaskState = {
  tasks: mockTasks, // Use mock data as initial state
};

const taskSlice = createSlice({
  name: "tasks",
  initialState,
  reducers: {
    // This action will replace the entire task list (e.g., after an API fetch)
    setTasks: (state, action: PayloadAction<Task[]>) => {
      state.tasks = action.payload;
    },
    addTask: (state, action: PayloadAction<Task>) => {
      state.tasks.push(action.payload);
    },
    removeTask: (state, action: PayloadAction<string>) => {
      state.tasks = state.tasks.filter((task) => task.id !== action.payload);
    },
    updateTask: (state, action: PayloadAction<Task>) => {
      const index = state.tasks.findIndex(
        (task) => task.id === action.payload.id
      );
      if (index !== -1) {
        state.tasks[index] = action.payload;
      }
    },
  },
});

export const { setTasks, addTask, removeTask, updateTask } = taskSlice.actions;

export default taskSlice.reducer;
