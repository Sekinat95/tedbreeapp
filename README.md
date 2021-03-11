## About My implemeentation

Please use both postman as well as the .http file for testing:
business login url:

POST http://localhost:8000/V1/login
Content-Type: application/json

{
"email": "sekinat95@gmail.com",
"password": "password"
}

guest login url:

POST http://localhost:8000/V1/guest/login
Content-Type: application/json

{
"email": "lvon@example.com",
"password": "password"
}

## Other information

I have answered all questions save for the following two:

-   guest post an application for a job
-   business sees all applications for each of their jobs
