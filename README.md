## Get Started

This guide will walk you through the steps needed to get this project up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

- Docker
- Docker Compose

### Building the Docker Environment

Build and start the containers:

```
docker-compose up -d --build
```

### Installing Dependencies

```
docker-compose exec app sh
composer install
```

### Database Setup

Set up the database:

```
bin/cake migrations migrate
```

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

### Authentication

Use JWT authentication for the authentication function.
API to obtain a token:

```
curl --location --request POST 'http://localhost:34251/users/login.json' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data-urlencode 'email=minhnd1@gmail.com' \
--data-urlencode 'password=123qweASD'
```

Add bearer token to APIs requiring authentication.

TODO: pls summarize how to check "Authentication" bahavior

### Article Management

Article Management includes functionalities such as add, edit, and delete. The functionalities to view the list and
details of articles do not require authentication.

#### Retrieve All Articles (GET)

```
curl --location --request GET 'http://localhost:34251/articles.json'
```

#### Retrieve a Single Article (GET)

```
curl --location --request GET 'http://localhost:34251/articles/2.json'
```

#### Create an Article (POST)

```
curl --location --request POST 'http://localhost:34251/articles.json' \
--header 'Authorization: xxx' \
--header 'Content-Type: application/json' \
--data-raw '{
    "user_id": 1,
    "title": "test",
    "body": "today, I'\''m working in haposoft.",
    "created_at": "2024-02-03T00:00:00",
    "updated_at": "2024-02-26T00:00:00"
}'
```

#### Update an Article (PUT)

```
curl --location --request PUT 'http://localhost:34251/articles/2.json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "user_id": 1,
    "title": "test",
    "body": "today, I'\''m working in haposoft.",
    "created_at": "2024-02-03T00:00:00",
    "updated_at": "2024-02-26T00:00:00"
}'
```

#### Delete an Article (DELETE)

```
curl --location --request DELETE 'http://localhost:34251/articles/1.json' \
--header 'Authorization: xxx' \
--header 'Content-Type: application/json' \
--data-raw '{
    "user_id": 1,
    "title": "test 2",
    "body": "today, I'\''m working in haposoft.",
    "created_at": "2024-02-03T00:00:00",
    "updated_at": "2024-02-26T00:00:00"
}'
```

TODO: pls summarize how to check "Article Management" bahavior

### Like Feature

Create a `likes` table to establish a relationship between `user_id` and `article_id`, ensuring that one article can
only have one like. Add a `likes` function in the `ArticlesController`.

#### Like an Article (POST)

```
curl --location --request POST 'http://localhost:34251/articles/like/2.json' \
--header 'Authorization: xxx' \
```

In the `get list` and `get detail article` functions, add the `like_count` field to display the number of likes for each article.

TODO: pls summarize how to check "Like Feature" bahavior
