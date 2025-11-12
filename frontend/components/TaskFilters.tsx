"use client";

import { Task } from "@/lib/types";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Label } from "@/components/ui/label";

export type SortOrder = "newest" | "oldest";
export type RatingFilter = "all" | "0" | "1" | "2" | "3" | "4" | "5";
export type TopicFilter = "all";

export interface TaskFiltersState {
  topic: TopicFilter | string;
  rating: RatingFilter;
  sort: SortOrder;
}

interface TaskFiltersProps {
  tasks: Task[]; // Used to dynamically find all unique topics
  filters: TaskFiltersState;
  onFiltersChange: (newFilters: Partial<TaskFiltersState>) => void;
}

export function TaskFilters({
  tasks,
  filters,
  onFiltersChange,
}: TaskFiltersProps) {
  // Get all unique topics from the task list
  const topics = Array.from(new Set(tasks.map((task) => task.topic)));

  return (
    <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-card border rounded-lg">
      {/* Filter by Topic */}
      <div className="space-y-2">
        <Label htmlFor="filter-topic">Filter by Topic</Label>
        <Select
          value={filters.topic}
          onValueChange={(value) => onFiltersChange({ topic: value })}
        >
          <SelectTrigger id="filter-topic">
            <SelectValue placeholder="Select topic" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">All Topics</SelectItem>
            {topics.map((topic) => (
              <SelectItem key={topic} value={topic}>
                {topic}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      {/* Filter by Rating */}
      <div className="space-y-2">
        <Label htmlFor="filter-rating">Filter by Rating</Label>
        <Select
          value={filters.rating}
          onValueChange={(value) =>
            onFiltersChange({ rating: value as RatingFilter })
          }
        >
          <SelectTrigger id="filter-rating">
            <SelectValue placeholder="Select rating" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">All Ratings</SelectItem>
            {[5, 4, 3, 2, 1, 0].map((num) => (
              <SelectItem key={num} value={String(num)}>
                {num} {num === 1 ? "Star" : "Stars"}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>

      {/* Sort by Date */}
      <div className="space-y-2">
        <Label htmlFor="sort-date">Sort by Date</Label>
        <Select
          value={filters.sort}
          onValueChange={(value) =>
            onFiltersChange({ sort: value as SortOrder })
          }
        >
          <SelectTrigger id="sort-date">
            <SelectValue placeholder="Sort by" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="newest">Newest First</SelectItem>
            <SelectItem value="oldest">Oldest First</SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>
  );
}
