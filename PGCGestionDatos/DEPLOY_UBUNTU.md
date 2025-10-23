# 🚀 Guía de Despliegue en Ubuntu Server (Azure)

Esta guía te llevará paso a paso para desplegar tu aplicación Laravel con Docker en un servidor Ubuntu en Azure.

---

## 📋 Prerrequisitos en Azure

### 1. Crear máquina virtual Ubuntu

1. Entra al portal de Azure
2. Crea una nueva VM:
   - **Imagen**: Ubuntu Server 22.04 LTS
   - **Tamaño**: Mínimo Standard B2s (2 vCPUs, 4 GB RAM)
   - **Autenticación**: SSH con clave pública
   - **Puertos de entrada**: 22 (SSH), 80 (HTTP), 443 (HTTPS)

### 2. Configurar Network Security Group (NSG)

Añade reglas de entrada:
- **Puerto 22**: SSH (desde tu IP)
- **Puerto 80**: HTTP (desde cualquier IP)
- **Puerto 443**: HTTPS (desde cualquier IP)

---

## 🔧 Paso 1: Conectarse al servidor

```bash
ssh azureuser@TU_IP_PUBLICA
```

Reemplaza `azureuser` por tu usuario y `TU_IP_PUBLICA` por la IP de tu VM.

---

## 🐳 Paso 2: Instalar Docker y Docker Compose

```bash
# Actualizar paquetes
sudo apt update && sudo apt upgrade -y

# Instalar dependencias
sudo apt install -y ca-certificates curl gnupg lsb-release

# Añadir repositorio oficial de Docker
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instalar Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Verificar instalación
docker --version
docker compose version

# Añadir usuario al grupo docker (para no usar sudo)
sudo usermod -aG docker $USER

# Aplicar cambios
newgrp docker
```

---

## 📦 Paso 3: Clonar el repositorio

```bash
# Crear directorio para apps
mkdir -p ~/apps
cd ~/apps

# Clonar el repositorio
git clone https://github.com/lopezns/SciGOP-prod.git
cd SciGOP-prod/PGCGestionDatos
```

---

## ⚙️ Paso 4: Configurar variables de entorno

```bash
# Copiar archivo de ejemplo
cp .env.docker.example .env

# Editar archivo .env
nano .env
```

### Configuración recomendada para PRODUCCIÓN:

```env
APP_NAME=SCIGOP
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://TU_IP_PUBLICA

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=scigop_db
DB_USERNAME=scigop_user
DB_PASSWORD=CAMBIA_ESTA_PASSWORD_SEGURA

CACHE_STORE=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
```

⚠️ **IMPORTANTE**: Cambia `TU_IP_PUBLICA` y `CAMBIA_ESTA_PASSWORD_SEGURA`.

Guarda con: `Ctrl + O`, `Enter`, `Ctrl + X`

---

## 🔐 Paso 5: Actualizar credenciales de MySQL

Edita `docker-compose.yml` para que coincida con el `.env`:

```bash
nano docker-compose.yml
```

Busca la sección `db` y actualiza:

```yaml
  db:
    image: mysql:8.0
    container_name: laravel_mysql
    restart: unless-stopped
    ports:
      - "3307:3306"
    environment:
      MYSQL_ROOT_PASSWORD: PASSWORD_ROOT_SEGURA
      MYSQL_DATABASE: scigop_db
      MYSQL_USER: scigop_user
      MYSQL_PASSWORD: CAMBIA_ESTA_PASSWORD_SEGURA  # ⚠️ Debe coincidir con .env
```

**Además**, cambia el puerto de Nginx para exponer el 80:

```yaml
  nginx:
    image: nginx:alpine
    container_name: laravel_nginx
    restart: unless-stopped
    ports:
      - "80:80"  # ⚠️ Cambiar de 8080:80 a 80:80
```

Guarda con: `Ctrl + O`, `Enter`, `Ctrl + X`

---

## 🏗️ Paso 6: Construir y levantar contenedores

```bash
# Construir imágenes
docker compose build --no-cache

# Levantar servicios
docker compose up -d

# Verificar que estén corriendo
docker compose ps
```

Todos los contenedores deben mostrar estado `Up`.

---

## 🔑 Paso 7: Configurar Laravel

```bash
# Generar clave de aplicación
docker compose exec app php artisan key:generate

# Ejecutar migraciones
docker compose exec app php artisan migrate --force

# Cachear configuración para producción
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan optimize

# Verificar permisos
docker compose exec app chmod -R 775 storage bootstrap/cache
```

---

## 🌐 Paso 8: Configurar firewall (UFW)

```bash
# Habilitar firewall
sudo ufw allow 22/tcp     # SSH
sudo ufw allow 80/tcp     # HTTP
sudo ufw allow 443/tcp    # HTTPS (para SSL futuro)
sudo ufw enable

# Verificar estado
sudo ufw status
```

---

## ✅ Paso 9: Verificar instalación

```bash
# Ver logs
docker compose logs -f

# Probar conexión
curl -I http://localhost
```

Deberías ver `HTTP/1.1 200 OK`.

---

## 🌍 Paso 10: Acceder desde Internet

Abre tu navegador y ve a:

```
http://TU_IP_PUBLICA
```

Para ver phpMyAdmin (solo para desarrollo, deshabilítalo en producción):

```
http://TU_IP_PUBLICA:8081
```

Para obtener tu IP pública desde el servidor:

```bash
curl ifconfig.me
```

---

## 🔒 Seguridad Adicional (RECOMENDADO)

### 1. Deshabilitar phpMyAdmin en producción

Edita `docker-compose.yml` y comenta o elimina el servicio `phpmyadmin`:

```yaml
#  phpmyadmin:
#    image: phpmyadmin:latest
#    ...
```

Luego reinicia:

```bash
docker compose down
docker compose up -d
```

### 2. Configurar SSL con Let's Encrypt (HTTPS)

Primero, necesitas un dominio apuntando a tu IP.

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtener certificado (reemplaza tudominio.com)
sudo certbot --nginx -d tudominio.com
```

### 3. Cambiar contraseñas por defecto

- Usa contraseñas fuertes en `.env` y `docker-compose.yml`
- Considera usar variables de entorno del sistema para secretos

### 4. Configurar backups automáticos

Crea un script de backup:

```bash
nano ~/backup-db.sh
```

Contenido:

```bash
#!/bin/bash
BACKUP_DIR="$HOME/backups"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

cd ~/apps/SciGOP-prod/PGCGestionDatos
docker compose exec -T db mysqldump -u scigop_user -pTU_PASSWORD scigop_db > "$BACKUP_DIR/backup_$DATE.sql"

# Mantener solo los últimos 7 backups
ls -t $BACKUP_DIR/backup_*.sql | tail -n +8 | xargs rm -f

echo "Backup completado: backup_$DATE.sql"
```

Dar permisos y añadir a cron:

```bash
chmod +x ~/backup-db.sh

# Ejecutar diariamente a las 2 AM
crontab -e
# Añade esta línea:
0 2 * * * /home/azureuser/backup-db.sh
```

---

## 🔄 Actualizar la aplicación

Cuando hagas cambios en el código:

```bash
cd ~/apps/SciGOP-prod/PGCGestionDatos

# Descargar cambios
git pull origin main

# Reconstruir contenedores
docker compose down
docker compose build --no-cache
docker compose up -d

# Ejecutar migraciones nuevas
docker compose exec app php artisan migrate --force

# Limpiar y regenerar cachés
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

---

## 📊 Monitoreo

```bash
# Ver logs en tiempo real
docker compose logs -f

# Ver recursos utilizados
docker stats

# Ver solo logs de un servicio
docker compose logs -f nginx
docker compose logs -f app
docker compose logs -f db
```

---

## 🆘 Troubleshooting

### Error: Puerto 80 en uso

```bash
# Ver qué proceso usa el puerto
sudo lsof -i :80

# Si es Apache
sudo systemctl stop apache2
sudo systemctl disable apache2
```

### Error: Permisos en storage

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www:www storage bootstrap/cache
```

### Error: No se conecta a MySQL

```bash
# Ver logs de MySQL
docker compose logs db

# Verificar conexión desde el contenedor
docker compose exec app php artisan db:show
```

### Contenedor reiniciándose constantemente

```bash
# Ver logs del contenedor problemático
docker compose logs app

# Reiniciar todos los servicios
docker compose restart
```

---

## 📝 Comandos útiles

```bash
# Detener todos los contenedores
docker compose down

# Reiniciar un servicio específico
docker compose restart nginx

# Acceder al contenedor de la app
docker compose exec app bash

# Ver base de datos MySQL
docker compose exec db mysql -u scigop_user -p

# Limpiar todo (⚠️ elimina datos)
docker compose down -v
docker system prune -a
```

---

## ✅ Checklist final

- [ ] Docker y Docker Compose instalados
- [ ] Repositorio clonado
- [ ] `.env` configurado con credenciales seguras
- [ ] `docker-compose.yml` con puerto 80 y credenciales actualizadas
- [ ] Contenedores corriendo (`docker compose ps`)
- [ ] Migraciones ejecutadas
- [ ] Configuración cacheada
- [ ] Firewall UFW configurado
- [ ] Aplicación accesible desde IP pública
- [ ] phpMyAdmin deshabilitado en producción
- [ ] Backups automáticos configurados
- [ ] SSL configurado (si tienes dominio)

---

## 🎉 ¡Listo!

Tu aplicación Laravel está corriendo en producción con Docker.

**URL de acceso**: http://TU_IP_PUBLICA

Para soporte, revisa:
- **README_DOCKER.md** - Documentación completa
- **Logs**: `docker compose logs -f`

---

**Contacto**: Para dudas, revisa los logs o contacta al equipo de desarrollo.
