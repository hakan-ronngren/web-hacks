.PHONY: run kill build network test list-mock-requests browse staging clean

WEB_CONTAINER := web

# Run the web container
run: build kill
	docker run -d --rm --name $(WEB_CONTAINER) -p 8080:80 -v $$PWD/htdocs:/var/www/localhost/htdocs $(WEB_CONTAINER)

kill:
	docker ps --format "{{.Names}}" | grep -E "$(WEB_CONTAINER)" | while read -r c; do docker kill $$c; done

build:
	docker build -t $(WEB_CONTAINER) .

browse: run
	timeout 5.0s sh -c 'while ! curl -fs http://localhost:8080/ ; do sleep 1 ; done'
	open http://localhost:8080/       # This works on MacOS

clean: kill
	docker rmi $(WEB_CONTAINER) || true
