<?php

namespace App\Services;

use LucianoTonet\GroqPHP\Groq;

class GroqService
{
    protected $groq;

    public function __construct()
    {
        $this->groq = new Groq();
    }

    public function getAssistantResponse(string $message)
    {
        return $this->groq->chat()->completions()->create([
            'model' => 'llama3-8b-8192',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Anda adalah asisten yang sangat berpengetahuan tentang topik stunting. Jawab pertanyaan pengguna hanya jika terkait dengan stunting. Jika tidak, arahkan pengguna untuk menanyakan tentang stunting.',
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
        ]);
    }
}
