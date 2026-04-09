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

## Deployment

### With Docker Compose (Recommended)

A sample docker-compose for you, using a proxy for Docker socket to make things a tiny bit more secure:

```yaml
services:
  docker-socket-proxy:
    image: tecnativa/docker-socket-proxy:0.3.0
    environment:
      CONTAINERS: 1
      INFO: 1
      PING: 1
      VERSION: 1
      POST: 0
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock:ro
    tmpfs:
      - /run
    restart: unless-stopped

  docker-list:
    image: ghcr.io/jdamner/docker-list:main
    ports:
      - 80:8080
    environment:
      DOCKER_HOST: tcp://docker-socket-proxy:2375
      HOSTNAME: server.local
      EXCLUDE_PORT: 80
    depends_on:
      - docker-socket-proxy
    restart: unless-stopped
```


### Manual Docker Run

```bash
docker build -t docker-list .
docker run --rm -p 8080:8080 -v /var/run/docker.sock:/var/run/docker.sock docker-list
```

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