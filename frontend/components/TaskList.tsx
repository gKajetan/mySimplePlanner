"use client";

import { useMemo, useState } from "react";
import { useAppSelector } from "@/store/hooks";
import { Task } from "@/lib/types";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { StarRating } from "@/components/ui/star-rating";
import { TaskFilters, TaskFiltersState } from "@/components/TaskFilters";

// --- TaskCard Sub-Component ---
// (This is the same as before, just moved inside this file)
function TaskCard({ task }: { task: Task }) {
  return (
    <Dialog>
      <DialogTrigger asChild>
        <Card className="cursor-pointer hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors">
          <CardHeader>
            <CardTitle className="text-lg">{task.title}</CardTitle>
            <CardDescription>{task.topic}</CardDescription>
          </CardHeader>
          <CardFooter>
            <div className="flex justify-between items-center w-full">
              <span className="text-sm text-muted-foreground">
                Zobacz szczegóły
              </span>
              <StarRating
                value={task.importance}
                readOnly
                starClassName="size-4"
              />
            </div>
          </CardFooter>
        </Card>
      </DialogTrigger>
      <DialogContent className="sm:max-w-[425px]">
        <DialogHeader>
          <DialogTitle>{task.title}</DialogTitle>
          <DialogDescription>
            <strong>Temat:</strong> {task.topic}
          </DialogDescription>
        </DialogHeader>
        <div className="py-4 space-y-4">
          <div>
            <h4 className="font-medium mb-1">Opis</h4>
            <p className="text-sm text-muted-foreground">
              {task.description || "Nie podano opisu."}
            </p>
          </div>
          <div>
            <h4 className="font-medium mb-1">Ważność</h4>
            <StarRating value={task.importance} readOnly />
          </div>
          <div>
            <h4 className="font-medium mb-1">Utworzono</h4>
            <p className="text-sm text-muted-foreground">
              {new Date(task.createdAt).toLocaleString("pl-PL")}
            </p>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
}

// --- Main TaskList Component ---
export function TaskList() {
  // 1. Get ALL tasks from Redux
  const allTasks = useAppSelector((state) => state.tasks.tasks);

  // 2. Set up local state for filters
  const [filters, setFilters] = useState<TaskFiltersState>({
    topic: "all",
    rating: "all",
    sort: "newest",
  });

  const handleFiltersChange = (newFilters: Partial<TaskFiltersState>) => {
    setFilters((prev) => ({ ...prev, ...newFilters }));
  };

  // 3. Filter and sort tasks using useMemo for efficiency
  const filteredTasks = useMemo(() => {
    let tasks = [...allTasks];

    // Filter by topic
    if (filters.topic !== "all") {
      tasks = tasks.filter((task) => task.topic === filters.topic);
    }

    // Filter by rating
    if (filters.rating !== "all") {
      const ratingNum = parseInt(filters.rating);
      tasks = tasks.filter((task) => task.importance === ratingNum);
    }

    // Sort by date
    tasks.sort((a, b) => {
      const dateA = new Date(a.createdAt).getTime();
      const dateB = new Date(b.createdAt).getTime();
      return filters.sort === "newest" ? dateB - dateA : dateA - dateB;
    });

    return tasks;
  }, [allTasks, filters]);

  // 4. Render filters and the filtered list
  return (
    <div className="mt-6 space-y-4">
      <h2 className="text-2xl font-semibold">Twoje zadania</h2>

      <TaskFilters
        tasks={allTasks}
        filters={filters}
        onFiltersChange={handleFiltersChange}
      />

      {filteredTasks.length === 0 ? (
        <Card>
          <CardContent className="pt-6">
            <p className="text-center text-muted-foreground">
              Nie znaleziono zadań pasujących do filtrów.
            </p>
          </CardContent>
        </Card>
      ) : (
        <div className="space-y-4">
          {filteredTasks.map((task) => (
            <TaskCard key={task.id} task={task} />
          ))}
        </div>
      )}
    </div>
  );
}
