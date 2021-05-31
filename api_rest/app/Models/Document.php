<?php
/**
 * Document.php
 *
 * Document model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class Document {

    public ?int $id;
    public ?string $document_serial_id;
    public ?string $type;
    public ?int $user_id;
    public ?int $package_number;
    public ?string $signature_base64;

    /**
     * 
     * Constructor of the Document model object.
     * 
     * @param int $id The document identifier
     * @param string $document_serial_id The serial id of the document
     * @param string $type The type of the document
     * @param int $user_id The identifier of the owner of the document
     * @param int $package_number The package number (Number from 1 to 5)
     * @param string $signature_base64 The image of the signature in base64
     */
    public function __construct(int $id = null, string $document_serial_id = null, string $type = null,int $user_id = null,int $package_number = null, string $signature_base64 = null)
    {
        $this->id = $id;
        $this->document_serial_id = $document_serial_id;
        $this->type = $type;
        $this->user_id = $user_id;
        $this->package_number = $package_number;
        $this->signature_base64 = $signature_base64;
    }
}