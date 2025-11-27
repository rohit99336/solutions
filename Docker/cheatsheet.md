
---

# 🐳 **Docker Cheat Sheet**


# ⭐ 1) **Docker के बेसिक कमांड**

### ✔ Docker वर्ज़न चेक करें

```
docker --version
docker version
```

### ✔ Docker की जानकारी देखें

```
docker info
```

---

# ⭐ 2) **इमेज (Image) से जुड़े कमांड**

### ✔ इमेज डाउनलोड (Pull)

```
docker pull nginx
docker pull php:8.2-fpm
```

### ✔ सिस्टम में सभी इमेज देखें

```
docker images
```

### ✔ इमेज को डिलीट करें

```
docker rmi image_name
docker rmi image_id
```

### ✔ एक इमेज से नया कंटेनर चलाना

```
docker run image_name
```

---

# ⭐ 3) **कंटेनर (Container) से जुड़े कमांड**

### ✔ कंटेनर चलाएँ

```
docker run image_name
```

### ✔ कंटेनर को नाम के साथ चलाएँ

```
docker run --name myapp image_name
```

### ✔ बैकग्राउंड में कंटेनर चलाएँ (Detached mode)

```
docker run -d image_name
```

### ✔ पोर्ट मैप करके चलाएँ (Host → Container)

```
docker run -p 8080:80 nginx
```

### ✔ कंटेनर में कमांड रन करें

```
docker exec -it container_name bash
```

या

```
docker exec -it container_name sh
```

### ✔ कंटेनर लिस्ट देखें

```
docker ps              # चल रहे कंटेनर
docker ps -a           # सभी कंटेनर
```

### ✔ कंटेनर को बंद करें (Stop)

```
docker stop container_name
```

### ✔ कंटेनर को स्टार्ट करें

```
docker start container_name
```

### ✔ कंटेनर डिलीट करें

```
docker rm container_name
```

---

# ⭐ 4) **Docker Compose कमांड**

### ✔ Compose फ़ाइल चलाएँ

```
docker compose up
```

### ✔ बैकग्राउंड में चलाएँ

```
docker compose up -d
```

### ✔ Compose बंद करें

```
docker compose down
```

### ✔ कंटेनर / इमेज rebuild करें

```
docker compose up --build
```

---

# ⭐ 5) **लॉग देखना**

### ✔ सारे कंटेनर के लॉग

```
docker logs container_name
```

### ✔ लाइव लॉग (Follow mode)

```
docker logs -f container_name
```

---

# ⭐ 6) **इमेज बनाना (Build) — Dockerfile से**

### ✔ Dockerfile से इमेज बनाओ

```
docker build -t myapp .
```

### ✔ Cache bypass करके build

```
docker build --no-cache -t myapp .
```

---

# ⭐ 7) **कंटेनर के अंदर जाना**

```
docker exec -it container_name bash
```

यदि bash नहीं है:

```
docker exec -it container_name sh
```

---

# ⭐ 8) **सिस्टम साफ करना (Cleanup)**

### ✔ बंद कंटेनर हटाओ

```
docker container prune
```

### ✔ बेकार इमेज हटाओ

```
docker image prune
```

### ✔ पूरे सिस्टम का cleanup

```
docker system prune
docker system prune -a   # इमेज + कंटेनर सब हटेगा
```

---

# ⭐ 9) **Volumes**

### ✔ सभी volumes देखें

```
docker volume ls
```

### ✔ Volume बनाएँ

```
docker volume create mydata
```

### ✔ Volume हटाएँ

```
docker volume rm mydata
```

---

# ⭐ 10) **नेटवर्क्स**

### ✔ सभी networks देखें

```
docker network ls
```

### ✔ नया network बनाएँ

```
docker network create mynetwork
```

### ✔ नेटवर्क हटाएँ

```
docker network rm mynetwork
```

---

# ⭐ Bonus: Laravel Project का Docker Run Example

```
docker compose up -d
docker compose exec app php artisan migrate
docker compose exec app php artisan serve
```

---
