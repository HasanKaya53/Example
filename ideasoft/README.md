İdeasoft için oluşturulmuş case çalışmaları
```
Kurulum
```
1. docker-compose.yml dosyasını çalıştırın.
```sh
  docker-compose up -d
```

2. Daha sonra projenin bulunduğu dizine gidin.
```sh
  cd ideasoft
```


3. Proje bağımlılıklarını yükleyin.
```sh
  composer install
```

4. Proje Seed ve Migrationlarını çalıştırın.
```sh
  php artisan migrate --seed
```

5.Eğer mac kullanıyorsanız aşağıdaki komutu çalıştırın. ve aşağıdaki satırları ekleyin.
```sh
  sudo nano /etc/hosts
```

```
127.0.0.1       task.local
```
6.Eğer windows kullanıyorsanız aşağıdaki komutu çalıştırın. ve aşağıdaki satırları ekleyin.
```sh
  notepad C:\Windows\System32\drivers\etc\hosts
```

```
127.0.0.1       task.local
```

Bilgilendirme
```
- Projeyi çalıştırmak için docker-compose.yml dosyasını çalıştırmanız gerekmektedir.
- Projede kullanılan veritabanı bilgileri aşağıdaki gibidir.
```

```
phpmyadmin: localhost:8080
username: root
password: root

# mongodb için express-mongo kullanılmadı. MongoDB Compass ile bağlanabilirsiniz.
MongoDB: localhost:27017
username: root
password: root

Projeye erişim için: http://task.local
```


```
- Kullanılan programlama teknolojileri ve açıklamalar

1. PHP 8.4 
2. Laravel 11 (Aslında bu task için uygun olan framework Laravel değil, Slim veya symfony daha uygun olabilirdi. Ancak bu taskte, benim düşündüğüm eklemeleri kolayca yapabilmem için bunu tercih ettim.)
3. MongoDB (MongoDB Compass ile bağlanabilirsiniz. nosql veri tabanı bilgilerimi size aktarmam açısından orders collectionunu burda oluşturdum. )
4. MySQL (phpmyadmin üzerinden bağlanabilirsiniz. Aslında burda tercih ettiğim ilişkisel veri tabanının çok bir önemi olduğunu düşünmüyorum. Psql de kullanılabilirdi.)
5. Docker (Docker ile projeyi çalıştırmak için docker-compose.yml dosyasını çalıştırmanız gerekmektedir.)
6. Redis (Redisi ürün indirimini servisinde, cache yapmak için kullandım.) 
```

