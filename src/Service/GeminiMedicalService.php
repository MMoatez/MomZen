<?php 
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class GeminiMedicalService
{
    private HttpClientInterface $httpClient;
    private string $apiKey = "AIzaSyCVQHI_ArRIWqOmqDwi0D1cC5kBKXq-gVI"; // 🔴 Replace with your actual API key
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function analyzeMedicalHistory(string $historique): ?string
{
    $prompt = "Tu es un assistant médical expert en analyse de dossiers médicaux. 
    Ta tâche est d'examiner les informations médicales fournies et de déterminer si la patiente est enceinte ou non.

    ### **Consignes** :
    Si la patiente est enceinte, indique précisément :
    - Le **nombre estimé de semaines de grossesse**.
    - **Toute indication médicale** pouvant confirmer ou infirmer cette grossesse.
    - **Les éventuels examens complémentaires recommandés** (échographie, test sanguin, etc.).
    
    Si aucune information claire ne permet de conclure à une grossesse, réponds uniquement avec **'Impossible de déterminer'**.

    Ne donne **aucune information non confirmée**. Si les informations médicales sont **ambiguës**, recommande une **consultation médicale** pour un avis plus précis.

    **Format de réponse** :
    - Réponds sous forme de **phrase complète et compréhensible**.
    - Utilise un langage **professionnel et médicalement exact**.
    - Ne fais **aucune supposition** en l'absence de preuves médicales.

    ### **Dossier médical de la patiente** :
    \"$historique\"

    Réponds en analysant attentivement les données ci-dessus.";


    // ✅ Use a different model for better reliability
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $this->apiKey;

    $maxRetries = 3; // ✅ Retries up to 3 times if the request fails
    $retryDelay = 2; // ✅ Waits 2 seconds before retrying

    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $data = $response->toArray();

            // ✅ Log API Response for Debugging
            $this->logger->info("Gemini API Response (Attempt: {$attempt})", [
                'status' => $statusCode,
                'response' => $data
            ]);

            // ✅ Ensure API returned expected data
            if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $this->logger->warning("Gemini API returned an unexpected response", ['response' => $data]);
                
                if ($attempt < $maxRetries) {
                    sleep($retryDelay);
                    continue; // Retry the request
                }

                return "Je n'ai pas pu analyser ces informations. Veuillez réessayer plus tard.";
            }

            return trim($data['candidates'][0]['content']['parts'][0]['text']); // ✅ Return clean response

        } catch (\Exception $e) {
            // ✅ Log API Errors
            $this->logger->error("Gemini API Exception (Attempt: {$attempt})", [
                'exception' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
            ]);

            if ($attempt < $maxRetries) {
                sleep($retryDelay);
                continue; // Retry the request
            }

            return "Une erreur technique est survenue. Veuillez réessayer plus tard.";
        }
    }

    return "Impossible de traiter votre demande pour le moment.";
}


}
