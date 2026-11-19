<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// Correo con el reporte del gimnasio en PDF adjunto.
// El remitente es el correo del sistema configurado en el .env (MAIL_FROM_ADDRESS).
class ReporteGimnasio extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $pdf,
        public string $fechaInicio,
        public string $fechaFin,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reporte FitControl del ' . $this->fechaInicio . ' al ' . $this->fechaFin,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reporte',
            with: [
                'fechaInicio' => $this->fechaInicio,
                'fechaFin' => $this->fechaFin,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdf, 'reporte-fitcontrol-' . $this->fechaInicio . '_a_' . $this->fechaFin . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
