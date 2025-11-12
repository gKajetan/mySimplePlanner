export interface Task {
  id: string;
  topic: string;
  title: string;
  description: string;
  importance: number; // 0-5
  createdAt: string; // Added for sorting/filtering by date
}
