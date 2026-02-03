---
description: Cómo desplegar la App en Google Cloud Run
---

# 🚀 Despliegue en Google Cloud Run (Paso a Paso)

Este es el proceso para subir tu aplicación a la infraestructura de nivel mundial de Google.

### 1. Requisitos Previos
*   Tener una cuenta en [Google Cloud Console](https://console.cloud.google.com/).
*   Tener instalado el **Google Cloud CLI** en tu computadora.
*   Haber creado un **Proyecto** en Google Cloud.

### 2. Preparar el Proyecto
Abre una terminal en la carpeta de tu proyecto y ejecuta:

```bash
# Iniciar sesión en Google Cloud
gcloud auth login

# Configurar tu proyecto (reemplaza [PROJECT_ID] por el tuyo)
gcloud config set project [PROJECT_ID]

# Habilitar los servicios necesarios (solo la primera vez)
gcloud services enable run.googleapis.com containerregistry.googleapis.com cloudbuild.googleapis.com
```

### 3. Desplegar (El comando mágico)
Solo necesitas ejecutar este comando. Google Build empaquetará todo usando el `Dockerfile` que ya creamos y lo subirá:

```bash
gcloud run deploy agenda-pos-pro --source . --platform managed --region us-central1 --allow-unauthenticated
```

### 4. Detalles Técnicos Importantes
*   **Puerto:** El Dockerfile ya está configurado para usar el puerto `8080`, que es el que Google Run espera.
*   **Base de Datos (SQLite):** Actualmente el software usa SQLite. **¡CUIDADO!** En Cloud Run, si borras o reinicias el contenedor, los datos de SQLite se perderán porque el disco es temporal. Para el lanzamiento final real, te ayudaré a conectarlo a **Cloud SQL (MySQL)** para que tus datos estén seguros para siempre.
*   **PHP Version:** Estamos usando la imagen de PHP 7.4-Apache, garantizando compatibilidad total con tu código.

### 5. ¿Y si quiero ver errores?
Si algo no carga, puedes ver los logs en tiempo real con:
```bash
gcloud beta run logs read --service agenda-pos-pro
```

---

**¡Ya tienes todo listo para ser global!** El Dockerfile y la configuración ya están en tu carpeta. ¿Deseas que revisemos algo más del código antes de que lances el comando?
