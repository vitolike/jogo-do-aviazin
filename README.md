# ✈️ Aviazin Game

Welcome to **Aviazin Game**! 🎮 This is an exciting Aviator-style game where you challenge your luck and test your reflexes. Built with 💻 **CodeIgniter 3** and **PHP 7.4**, the game utilizes **Canvas** on the frontend for a dynamic and interactive experience. 🚀

![Aviazin Game](image.JPG)

---

## DEMO URL
- https://jogodoaviao.games2api.xyz/play?token=d1d324eb4139d746845065e3f5e71675 (sometimes it may not be live).

## 🛠️ Features

- **🎲 Betting system:** Test your luck and see how far you can go before the "crash"! 📈
- **📉 Dynamic graph:** Visualize the curve in real-time and exit before it’s too late.
- **💵 Exciting multipliers:** Earn more the longer you hold on (if you're quick enough!).
- **🎨 Interactive interface:** Developed in Canvas for a smooth and responsive experience.

---

## 🚀 System Requirements

- **PHP:** 7.4 or higher
- **Web Server:** Nginx or Apache
- **Database:** MySQL
- **Libraries:** CodeIgniter 3

---

## ⚙️ Project Setup

1. Clone this repository:
   ```bash
   git clone https://github.com/vitolike/jogo-do-aviazin.git
   ```

2. Navigate to the project directory:
   ```bash
   cd jogo-do-aviazin
   ```

3. Configure the `.env` file with your database credentials:
   ```env
   # Database
   DB_CONNECTION=mysqli
   DB_HOST=localhost
   DB_DATABASE=aviaozin
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Import the database:
   - The SQL file is located in the `database/aviaozin.sql` folder.
   ```bash
   mysql -u root -p aviaozin < database/aviaozin.sql
   ```

5. Ensure project dependencies are installed.
   - Install Composer if needed:
     ```bash
     composer install
     ```

6. Configure the server:
   - For Nginx, use a configuration similar to:
     ```nginx
     server {
         listen 80;
         server_name localhost;
         root /path/to/jogo-do-aviazin;

         index index.php;

         location / {
             try_files $uri $uri/ /index.php?$query_string;
         }

         location ~ \.php$ {
             include snippets/fastcgi-php.conf;
             fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
             fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
             include fastcgi_params;
         }
     }
     ```

7. Run the local server:
   ```bash
   php -S localhost:8000
   ```

---

## 🎮 How to Play

1. Make a POST request to the endpoint:
   ```
   http://localhost
   ```

2. Use the following JSON request body:
   ```json
   {
       "method": "game_launch",
       "agent_code": "admin",
       "agent_token": "token",
       "balance": 1000,
       "user_code": "test",
       "game_code": "sports",
       "lang": "pt"
   }
   ```

3. The response will be similar to:
   ```json
   {
       "status": 1,
       "launch_url": "localhost/play?token=xxxx"
   }
   ```

4. Access the returned URL in your browser to start the game!

---

## 📂 Project Structure

```
|-- application/
|   |-- controllers/
|   |-- models/
|   |-- views/
|-- assets/
|   |-- css/
|   |-- js/
|   |-- images/
|-- database/
|   |-- aviaozin.sql
|-- .env
|-- index.php
|-- README.md
```

---

## ❤️ Contributions

Contributions are welcome! Feel free to open issues or submit pull requests to improve the game. 🌟

---

## 📜 License

This project is licensed under the [MIT License](LICENSE). FOR NON-COMMERCIAL USE ONLY FOR EDUCATIONAL PURPOSES.

---

## 📧 Contact

If you have any questions or suggestions, send an email to: **victorcostaoliveira@gmail.com**

---

**Have fun and good luck with the Aviazin Game! ✈️**
