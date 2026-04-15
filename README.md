# Rural WP — WordPress Theme

Tema WordPress para la landing de **Rural AI Meetup Anceu** (24–28 junio 2026).  
Incluye setup de desarrollo local con Docker y guía de deploy en VPS.

---

## Desarrollo local

```bash
git clone https://github.com/ruralhackers/ruralwp-wordpress.git
cd ruralwp-wordpress
docker compose up -d
```

Abre [http://localhost:8090](http://localhost:8090).

El tema se carga automáticamente desde `wp-content/themes/ruralwp/`.  
Cualquier cambio en los archivos del tema se refleja en el navegador sin reiniciar Docker.

---

## Deploy en VPS (DigitalOcean, Hetzner, etc.)

### 1. Prepara el servidor

```bash
# Ubuntu 22.04 — instala Docker
curl -fsSL https://get.docker.com | sh
```

### 2. Clona el repo y ajusta contraseñas

```bash
git clone https://github.com/ruralhackers/ruralwp-wordpress.git
cd ruralwp-wordpress
```

Edita `docker-compose.yml` y cambia los valores por defecto:

```yaml
MYSQL_PASSWORD: TU_PASSWORD_DB
MYSQL_ROOT_PASSWORD: TU_ROOT_PASSWORD
WORDPRESS_DB_PASSWORD: TU_PASSWORD_DB   # igual que MYSQL_PASSWORD
```

### 3. Arranca los contenedores

```bash
docker compose up -d
```

### 4. Instala WordPress

```bash
docker exec ruralwp-wordpress-1 wp core install \
  --url='https://tudominio.com' \
  --title='Rural AI Meetup Anceu' \
  --admin_user=admin \
  --admin_password=TU_PASSWORD_SEGURA \
  --admin_email=tu@email.com \
  --allow-root --path=/var/www/html
```

### 5. Activa el tema

```bash
docker exec ruralwp-wordpress-1 wp theme activate ruralwp \
  --allow-root --path=/var/www/html
```

### 6. SSL con Nginx + Certbot

```bash
# Instala Nginx y Certbot
apt install nginx certbot python3-certbot-nginx -y

# Configura Nginx como proxy inverso al puerto 8090
# /etc/nginx/sites-available/tudominio.com
server {
    server_name tudominio.com;
    location / {
        proxy_pass http://localhost:8090;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}

# Activa el site y genera el certificado SSL
ln -s /etc/nginx/sites-available/tudominio.com /etc/nginx/sites-enabled/
certbot --nginx -d tudominio.com
```

---

## Alternativa sin servidor — Hosting gestionado

Si no quieres gestionar un VPS, sube solo el tema a cualquier WordPress existente:

1. Comprime la carpeta `wp-content/themes/ruralwp/` en un `.zip`
2. Ve a `wp-admin → Apariencia → Temas → Subir tema`
3. Actívalo

Compatible con SiteGround, Kinsta, WP Engine, Hostinger y cualquier hosting WordPress estándar.

---

## Credenciales locales por defecto

| Variable | Valor |
|----------|-------|
| WordPress admin | `http://localhost:8090/wp-admin` |
| Usuario | `admin` |
| Contraseña | `admin` |
| DB name | `ruralwp` |
| DB user | `ruralwp` |
| DB password | `ruralwp` |

> ⚠️ Cambia todas las contraseñas antes de hacer deploy en producción.
