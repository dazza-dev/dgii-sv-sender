# DGII Sender 🇸🇻

Paquete para enviar documentos tributarios electrónicos (DTE) (Factura, Nota de remisión, Nota crédito, Nota débito y Comprobante de retención) al DGII (El Salvador).

## Instalación

```bash
composer require dazza-dev/dgii-sv-sender
```

## Uso

```php
use DazzaDev\DgiiSvSender\Sender;

// Instanciar el sender
$sender = new Sender();

// Entorno de pruebas (true para pruebas, false para producción)
$sender->setTestMode(true);

// NIT del emisor
$sender->setNit('06231103251021');
```

### Token de acceso

```php
$token = $sender->auth('nit', 'clave_api');
```

El token tiene una duración de 24 horas para producción y 12 horas para pruebas. cuando generas el token, debes guardarlo en un lugar seguro para usarlo en futuras solicitudes.

Si usas el token en la misma solicitud no es necesario que agregues el token al sender pero si vas a usarlo en futuras solicitudes aquí tienes un ejemplo:

```php
$sender->setBearerToken($token);
```

### Recepción DTE

La recepción de documentos puede ser procesada uno a uno o en lote:

```php
$sender->send(
    sendId: 1,
    version: 1,
    documentType: '01',
    generationCode: '9F7DAE10-6D7A-4DBB-8B85-566D38839456',
    signedJson: $signedJson
);
```

### Recepción DTE en Lote

```php
$sender->sendBatch(
    sendId: 1,
    version: 1,
    signedJsonDocuments: [$signedJson]
);
```

### Invalidar/Anular DTE

El servicio de invalidación es el componente que habilitará al contribuyente emisor para trasmitir la inactivación de un DTE recibido previamente.

```php
$sender->invalidate(
    sendId: 1,
    version: 1,
    signedJson: $signedJson
);
```

### Consultar DTE

```php
$sender->search(
    documentType: '01',
    generationCode: '9F7DAE10-6D7A-4DBB-8B85-566D38839456'
);
```

### Consulta de Lote DTE

```php
$sender->searchBatch(
    batchCode: '9F7DAE10-6D7A-4DBB-8B85-566D38839456'
);
```

### Evento Contingencia

El servicio de contingencia es el componente que habilitará al contribuyente emisor poder transmitir DTE que hayan sido generados durante un evento de fuerza mayor que imposibilite la transmisión de dichos documentos para su verificación; después de haber transmitido el Evento de Contingencia deberá hacer uso de los servicios uno a uno o por lote para transmitir los documentos generados en contingencia.

```php
$sender->contingencyEvent(
    json: $contingencyJson
);
```

## Firmar Documentos (DTE)

Para firmar DTE, puedes utilizar el paquete:

- [DGII Signer](https://github.com/dazza-dev/dgii-sv-signer)

## Contribuciones

Contribuciones son bienvenidas. Si encuentras algún error o tienes ideas para mejoras, por favor abre un issue o envía un pull request. Asegúrate de seguir las guías de contribución.

## Autor

DGII Sender fue creado por [DAZZA](https://github.com/dazza-dev).

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).
