# Variables
COMPOSE_FILE = compose.yaml

# Build and start services
up:
	docker-compose -f $(COMPOSE_FILE) up -d --wait

# Stop and remove containers
down:
	docker-compose -f $(COMPOSE_FILE) down --remove-orphans

# Restart services
restart: down up

# Show logs
logs:
	docker-compose -f $(COMPOSE_FILE) logs -f

# Show running containers
ps:
	docker-compose -f $(COMPOSE_FILE) ps

console:
	docker-compose -f $(COMPOSE_FILE) exec -it php bash

# Clean up everything (containers, volumes, networks, images)
clean:
	docker-compose -f $(COMPOSE_FILE) down --volumes --rmi all
