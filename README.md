# Docker List

A lightweight PHP service to discover and list all running Docker containers with their service links.

## What It Does

When deployed alongside Docker, this service scans running containers and displays their configured links in a clean, responsive web interface. It's useful for quickly accessing services running in your Docker environment.

**Features:**
- Lists all running Docker containers grouped by service name
- Displays service links/endpoints
- Shows the host that requests are coming from
- Responsive card-based UI
- Minimal dependencies

## Prerequisites

### Local Development
- PHP 8.4+ with the built-in web server
- Composer
- Docker socket access (for reading container data)

### Docker
- Docker & Docker Compose

## Local Setup

1. **Install dependencies:**
   ```bash
   composer install
   ```

2. **Start the PHP server:**
   ```bash
   php -S 0.0.0.0:8080 index.php
   ```

3. **Access the UI:**
   ```
   http://localhost:8080
   ```

### Local Docker Access

To connect to Docker locally when running the dev server, ensure the Docker socket is readable:
```bash
# Usually already set up by Docker Desktop/Engine installation
ls -la /var/run/docker.sock
```

## Docker Deployment

### With Docker Compose (Recommended)

1. **Start the service:**
   ```bash
   docker compose up -d
   ```

2. **Access at:**
   ```
   http://localhost:8080
   ```

3. **View logs:**
   ```bash
   docker compose logs -f docker-list
   ```

4. **Stop the service:**
   ```bash
   docker compose down
   ```

### Manual Docker Run

```bash
docker build -t docker-list .
docker run --rm -p 8080:8080 -v /var/run/docker.sock:/var/run/docker.sock docker-list
```

## Architecture

- **Dockerfile**: Multi-stage build using Composer for dependency installation, PHP 8.4 Alpine for runtime
- **index.php**: Main entry point; queries Docker API and renders HTML
- **src/Docker.php**: Custom wrapper around the Docker PHP client

## Dependencies

- `beluga-php/docker-php` - Docker PHP bindings
- `symfony/http-client` - HTTP client for Docker communication

## Security Notes

- This service requires access to the Docker socket, which grants significant control over your Docker environment
- Only expose this service on trusted networks
- The socket is mounted read-write to allow API communication
- Suitable for internal/private use only

## Development

### Project Structure
```
.
├── index.php              # Main application file
├── src/
│   └── Docker.php         # Docker service wrapper
├── composer.json          # PHP dependencies
├── Dockerfile             # Container image definition
├── docker-compose.yml     # Compose orchestration
└── README.md              # This file
```

### Adding Features

The main logic is in `index.php` and `src/Docker.php`. The UI styling is inline in `index.php` for portability.

## Pull Requests Welcome

The underlying Docker library used supports _so_ much more, but this came together in a couple of hours. 
It'd be nice to support remote docker connections, something with a saner security policy (ie read-only docker connection!)
and clever naming and discovering of hostnames etc. but this works for me. 