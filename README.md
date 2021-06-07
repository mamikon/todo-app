# Task management application

---
**Proposed User Story:**
"As a user, I want to have an ability to see a list of tasks for my day, so that I can do them one by one".
---

### Architecture Layers

- Core business logic - [src/TaskManagement/](https://github.com/mamikon/todo-app/tree/main/src/TaskManagement)
- UI and Configuration layers implemented with Symfony and API Platform
- Api Documentation placed at [http://localhost:8822/api/docs](http://localhost:8822/api/docs)
- Application and Ui layer connection implemented with CQRS Pattern
- Docker containerization configurations -
  ([docker-compose.yml](https://github.com/mamikon/todo-app/blob/main/docker-compose.yml),
  [Dockerfiles](https://github.com/mamikon/todo-app/tree/main/docker))