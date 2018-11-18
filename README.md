# my_trainer

Deploy:
  - Start containers: ``` docker-compose up -d --build ``` in ``` ./Docker ```
  - use command ```  docker-compose exec fpm  composer --working-dir=html update ``` to update dependecies
  - Edit env-file (``` ./.env ```) to add correct parameters
```sh
APP_ENV=dev
APP_SECRET=exempleexempleexempleexempleexemple
MAILER_URL=null://localhost
DATABASE_URL=pgsql://user:password@postgres:5432/my_trainer
```  
  - Use 
  ``` rm -rf ./../var/cache/ ; docker-compose exec fpm html/bin/console cache:clear ```
  or 
  ``` docker-compose exec fpm html/bin/console cache:clear ```
  - Enjoy yourself
