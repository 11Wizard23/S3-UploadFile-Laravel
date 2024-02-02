<?php


use Aws\S3\S3Client;

class SvcDigitalOceanSpaces
{
    private S3Client $cliente;

    public function __construct()
    {
        $this->cliente = new S3Client([
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'endpoint'    => \App\Service\DigitalOcean\env('S3_ENDPOINT'),
            'credentials' => [
                'key'    => \App\Service\DigitalOcean\env('S3_ACCESS_KEY_ID'),
                'secret' => \App\Service\DigitalOcean\env('S3_SECRET_ACCESS_KEY')
            ]
        ]);
    }

    /**
     * Metodo para subir un archivo a DigitalOcean
     *
     * @param $idArchivo
     * @param $nombreArchivo
     * @param $contenidoArchivo
     * @return string
     */
    public function subirArchivo($idArchivo, $nombreArchivo, $contenidoArchivo) : string
    {
        $resp = $this->cliente->putObject([
            'Bucket'   => \App\Service\DigitalOcean\env('S3_BUCKET'),
            'Key'      => 'oms/pqr/' . $idArchivo . '/' . $nombreArchivo,
            'Body'     => $contenidoArchivo,
            'ACL'      => 'public-read',
            'Metadata' => array(
                'x-amz-meta-my-key' => 'PQR-' . $idArchivo
            )
        ]);

        return $resp->get("ObjectURL") ?? "";
    }
}
