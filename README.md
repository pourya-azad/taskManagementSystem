# Task Management System

Execution video: [https://youtu.be/4-FE1PbyIm0](https://youtu.be/4-FE1PbyIm0)

## Project Description

This system allows users to create, manage, and update their tasks. Users can view task statuses in various states such as "In Progress," "Delayed," and "Completed." The system is designed to update task statuses in real-time.

## Features

1. **Task Creation and Management:**  
    Users can create new tasks via a form. Each task includes a title, description, due date, priority (High, Medium, Low), and status. Tasks are stored in a MySQL database.
    
2. **Task Updates:**  
    Users can view their task list and update statuses, e.g., changing a task from "In Progress" to "Completed." Updates are reflected in real-time on the dashboard.
    
3. **Task API:**  
    A RESTful API is provided to perform CRUD operations (Create, Read, Update, Delete) on tasks. Each endpoint is documented with its purpose and required parameters.
    
4. **Queue Management with Redis:**  
    High-priority tasks are pushed to a separate queue managed by Redis. This queue is processed rapidly, and users are notified accordingly.
    
5. **Real-Time Dashboard with Livewire:**  
    Using Livewire, a dashboard allows users to see task changes live. Users can add new tasks or update existing ones, and all changes automatically reflect without page reload.
    
6. **Laravel Echo and WebSocket Integration:**  
    With Redis and Laravel Echo, when tasks are added or updated, all connected users see these changes instantly in real-time.
    
7. **Error Handling:**  
    The system gracefully manages errors during task creation or queue processing and provides meaningful messages to users.
    
8. **Real-Time Notifications:**  
    When a user creates a high-priority task, all other users are immediately notified via WebSocket.
    
9. **Front-End:**  
    The UI is built using Blade templates or Vue.js, enabling dynamic display of tasks so users can view and edit tasks without page reloads.
    

## Additional Requirements

- Implement a logging system to track errors and issues.
    
- Use Git for version control and team collaboration.
    
- Design and implement basic tests and APIs to ensure proper functionality.
    

---
