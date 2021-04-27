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
    public ?string $document_serial_number;
    public ?string $type;
    public ?int $user_id;
    public ?string $signature_base64;

    /**
     * 
     * Constructor of the Document model object.
     * 
     * @param int $id The document identifier
     * @param string $document_serial_number The serial number of the document
     * @param string $type The type of the document
     * @param int $user_id The identifier of the owner of the document
     */
    public function __construct(int $id = null, string $document_serial_number = null, string $type = null,int $user_id = null, string $signature_base64 = null)
    {
        $this->id = $id;
        $this->document_serial_number = $document_serial_number;
        $this->type = $type;
        $this->user_id = $user_id;
        $this->signature_base64 = $signature_base64;
    }
}