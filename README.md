# Test TVMaze Project

## Local Development Setup

### Prerequisites
- Docker installed on your local machine

### Installation

1. Clone the repository:
   ```bash
git clone https://github.com/nestermaks/test-tvmaze.git
cd test-tvmaze
   ```

2. Install PHP dependencies using Docker:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

3. Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```

4. Generate the application key:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    php artisan key:generate
```

### Running the application

1. Start the application using Docker:
```bash
./vendor/bin/sail up -d
```

2. Once the application is running, open a terminal or command prompt. Use curl or any HTTP client to make a search query:
```bash
curl "http://localhost/?q=deadwood"
```
Replace deadwood with the TV show name you want to search for. You will receive a JSON response with the search results. Also you can use your browser or Postman.

### Running tests

1. Run the tests using Docker:
```bash
./vendor/bin/sail test
```
