
# Install lumon

```bash
docker run --rm -i -v $(pwd):/app composer:2.0.14 create-project --prefer-dist laravel/lumen deployment-server
```

docker-compose -p deploy-manager build --build-arg DB_DATABASE="app" 
docker-compose -p deploy-manager up


# Usage

```bash
curl -XPOST localhost:7024/api/v1/deploy/stage/canery
curl -XPOST localhost:7024/api/v1/deploy/stage/bg
curl -XPOST localhost:7024/api/v1/deploy/production/canery/98h2q56
curl -XPOST localhost:7024/api/v1/deploy/production/bg/3h8c3jd
```