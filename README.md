
## How to setup Ollert Application
- Copy .env.example to .env file
- Run **composer install**
- Run **php artisan key:generate**
- Run **php artisan migrate**
- Run **php artisan passport::install**
- Copy Client Id = 2 and Client Id Secret to .env file
- Change your APP_URL according to your development URL

## How to test login 
- create a test user using php tinker
- Use a application such as **Insomnia** or **Postman** to test
- To Test the login make a post request to **{{YOUR_APP_URL}}/api/v1/auth/login** and add email = {{user_email}}, password = {{user_password}} as a query string
- if the request is successful, a password grant token along with other information will be returned 