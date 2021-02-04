# Laravel テスト駆動開発  
Laravel テスト駆動開発の練習  
ブログサービスをテスト駆動開発で進めていく


```
docker-compose up -d
```

```
docker-compose exec php bash
```

```
docker stop $(docker ps -q)
```

```
vendor/bin/phpunit
vendor/bin/phpunit --filter=BlogViewControllerTest
vendor/bin/phpunit --filter=UserLoginControllerTest
docker-compose exec php vendor/bin/phpunit --filter=UserLoginControllerTest
```