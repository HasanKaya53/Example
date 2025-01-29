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
Connection String: mongodb://root:root@localhost:27017/

#redis için:
Redis: http://localhost:8081
password: YOK

# servis güvenliği için basic auth kullanılmıştır.
username: admin
password: admin

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
7. Swagger (API dokümantasyonu için kullanıldı.) 
```


```
- Proje Hakkında Notlar'

1. Mysql tarafında 2 tablo kullandım.
   - products tablosu: Ürünlerin bilgilerini tuttuğum tablo.
   - customers tablosu: Müşteri bilgilerini tuttuğum tablo.
2. MongoDB tarafında 2 Collection kullandım.
   - orders Collection: Sipariş bilgilerini burda tutuyorum.
   - discounts Collection: İndirim bilgilerini burda tutuyorum.
3. Redis tarafında ürünlerin hesaplanmış indirimlerini tutuyorum.

4. İndirim kurallarını tutmak için MongoDB kullandım. Çünkü indirim kurallarını daha dinamik bir şekilde tutmak istedim. İleride bu kuralların değişmesi durumunda, MongoDB üzerinden güncelleme yapılabilir. İlişkisel bir veritabanında tutmak istemedim çünkü indirim kurallarında çok fazla değişiklik olabilir ve bu durumda ilişkisel veritabanında güncelleme yapmak zor olabilir. MongoDB'de ise bu durum daha kolay olacaktır.

5. İndirimlerin hesaplaması için kullandığımız serviste, önce ürün indirimlerini yapıyorum. Son olarak tüm sepete yapılan %10 kuralını uyguluyorum. Burda genel alışveriş sitelerini incelediğimde bu şekilde bir senaryo çıkıyor. Fakat mongoDB tarafında bulunan discounts collectionu içindeki order kısmından bu sıralama ayarı yapılabilir. 
Örneğin 1000 TL bir alışveriş yapıldı. Ürün indirimi olarak %20 yapıldı. bu durumda sepet miktarı 800 TL olacaktır. Toplam sepet 1000 TL altında kaldığı için, %10 indirim uygulanmayacaktır.

6. JWT ile servis kontrolu yapılabilir. Giriş yapma kontrolleri olmadığı için JWT kullanmadım. Onun yerine basic auth kullandım, bu yöndem daha az güvenli fakat yine de bir güvenlik eklemek istedim.

7. Swagger ile API dokümantasyonu yapıldı. http://task.local/api/documentation adresinden erişebilirsiniz.) 
Auth bilgileri:
username: admin
password: admin  
```
