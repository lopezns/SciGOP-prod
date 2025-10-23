# Docker Setup para Laravel con MySQL

Configuración completa de Docker para proyecto Laravel con PHP 8.2, MySQL 8.0 y Nginx.

## 📋 Requisitos

- Docker Desktop (Windows/Mac) o Docker Engine (Linux)
- Docker Compose
- Git

---

## 🚀 Ejecución Local (Windows/Mac/Linux)

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/tu-proyecto.git
cd tu-proyecto
```

### 2. Configurar variables de entorno

Crea un archivo `.env` basado en `.env.example`:

```bash
cp .env.example .env
```

Edita el archivo `.env` con las credenciales de Docker:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=scigop_db
DB_USERNAME=scigop_user
DB_PASSWORD=scigop_password
```

### 3. Construir y ejecutar los contenedores

```bash
# Construir las imágenes
docker compose build

# Levantar los servicios
docker compose up -d
```

### 4. Instalar dependencias y configurar Laravel

```bash
# Acceder al contenedor de la app
docker compose exec app bash

# Dentro del contenedor:
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed  # Si tienes seeders
exit
```

### 5. Acceder a la aplicación

- **Aplicación**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

### Comandos útiles

```bash
# Ver logs
docker compose logs -f

# Detener contenedores
docker compose down

# Detener y eliminar volúmenes (⚠️ elimina datos de BD)
docker compose down -v

# Ejecutar comandos Artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear

# Acceder a MySQL
docker compose exec db mysql -u scigop_user -pscigop_password scigop_db
```

---

## 🌐 Despliegue en Servidor Ubuntu (Azure)

### Prerrequisitos en Azure

1. Crear una VM Ubuntu 22.04 LTS
2. Abrir puertos en el Network Security Group:
   - Puerto 80 (HTTP)
   - Puerto 443 (HTTPS, opcional)
   - Puerto 22 (SSH)

### 1. Conectarse al servidor

```bash
ssh usuario@IP_PUBLICA_SERVIDOR
```

### 2. Instalar Docker y Docker Compose

```bash
# Actualizar paquetes
sudo apt update && sudo apt upgrade -y

# Instalar dependencias
sudo apt install -y ca-certificates curl gnupg lsb-release

# Agregar repositorio oficial de Docker
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

# Agregar usuario al grupo docker (opcional, para no usar sudo)
sudo usermod -aG docker $USER
newgrp docker
```

### 3. Clonar el proyecto

```bash
# Crear directorio para aplicaciones
mkdir -p ~/apps
cd ~/apps

# Clonar repositorio
git clone https://github.com/tu-usuario/tu-proyecto.git
cd tu-proyecto
```

### 4. Configurar variables de entorno para producción

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Editar con nano o vi
nano .env
```

Configuración recomendada para producción:

```env
APP_NAME=SCIGOP
APP_ENV=production
APP_KEY=  # Se generará después
APP_DEBUG=false
APP_URL=http://IP_PUBLICA

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=scigop_db
DB_USERNAME=scigop_user
DB_PASSWORD=TU_PASSWORD_SEGURO_AQUI
```

**⚠️ Importante**: Cambia las contraseñas en `.env` Y en `docker-compose.yml` (sección `db`).

### 5. Modificar docker-compose.yml para producción

Edita `docker-compose.yml` para exponer puerto 80:

```bash
nano docker-compose.yml
```

Cambia la línea del servicio `nginx`:

```yaml
ports:
  - "80:80"  # Cambiar de 8080:80 a 80:80
```

### 6. Construir y ejecutar en producción

```bash
# Construir imágenes
docker compose build --no-cache

# Levantar servicios
docker compose up -d

# Verificar que estén corriendo
docker compose ps
```

### 7. Configurar Laravel

```bash
# Generar clave de aplicación
docker compose exec app php artisan key:generate

# Ejecutar migraciones
docker compose exec app php artisan migrate --force

# Optimizar para producción
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan optimize
```

### 8. Acceder desde Internet

- **Aplicación**: http://IP_PUBLICA_SERVIDOR
- **phpMyAdmin**: http://IP_PUBLICA_SERVIDOR:8081

Para obtener la IP pública:

```bash
curl ifconfig.me
```

### 9. Configurar firewall (UFW)

```bash
# Habilitar firewall
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS (opcional)
sudo ufw enable
sudo ufw status
```

---

## 🔄 Actualizar aplicación en producción

```bash
cd ~/apps/tu-proyecto

# Descargar cambios
git pull origin main

# Reconstruir contenedores
docker compose down
docker compose build --no-cache
docker compose up -d

# Ejecutar migraciones si hay
docker compose exec app php artisan migrate --force

# Limpiar cachés
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

---

## 🛡️ Seguridad Adicional (Recomendado)

### 1. Usar HTTPS con Let's Encrypt

Instala Certbot y configura SSL:

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d tudominio.com
```

### 2. Cambiar contraseñas por defecto

Edita `docker-compose.yml` y `.env` con contraseñas fuertes.

### 3. Deshabilitar phpMyAdmin en producción

Comenta o elimina el servicio `phpmyadmin` en `docker-compose.yml`.

---

## 📊 Monitoreo y Logs

```bash
# Ver logs en tiempo real
docker compose logs -f

# Ver logs de un servicio específico
docker compose logs -f nginx
docker compose logs -f app
docker compose logs -f db

# Ver recursos utilizados
docker stats
```

---

## 🗄️ Backup de Base de Datos

```bash
# Crear backup
docker compose exec db mysqldump -u scigop_user -pscigop_password scigop_db > backup_$(date +%Y%m%d).sql

# Restaurar backup
docker compose exec -T db mysql -u scigop_user -pscigop_password scigop_db < backup_20251023.sql
```

---

## 🆘 Troubleshooting

### Error de permisos en storage

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### No se conecta a la base de datos

```bash
# Verificar que el contenedor de MySQL esté corriendo
docker compose ps

# Ver logs de MySQL
docker compose logs db

# Probar conexión
docker compose exec app php artisan db:show
```

### Puerto 80 ya está en uso

```bash
# Ver qué proceso usa el puerto 80
sudo lsof -i :80

# Detener Apache si está instalado
sudo systemctl stop apache2
sudo systemctl disable apache2
```

---

## 📝 Notas

- El volumen `mysql_data` persiste los datos de la base de datos entre reinicios
- Los precios se manejan en **Pesos Colombianos (COP)** con formato de miles (ejemplo: 3.000)
- En producción, considera usar Docker Swarm o Kubernetes para alta disponibilidad

---

## 📞 Soporte

Para problemas o preguntas, contacta al equipo de desarrollo.
