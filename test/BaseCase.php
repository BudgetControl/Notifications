<?php

namespace BudgetControl\Test;


class BaseCase extends \PHPUnit\Framework\TestCase
{

    public static function setUpBeforeClass(): void
    {
        // Configura il reporting degli errori prima di eseguire i test
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }

    public function invokeMailhog($testData) : void {

        $client = new \GuzzleHttp\Client();
        $mailhogResponse = $client->request('GET', 'http://localhost:8025/api/v2/messages', [
            'query' => [
                'limit' => 10
            ]
        ]);

        $emails = json_decode($mailhogResponse->getBody()->getContents(), true);

        // Find our test email
        $emailFound = false;
        foreach ($emails['items'] as $email) {
            if ($email['Content']['Headers']['Subject'][0] === $testData['subject'] &&
                $email['Content']['Headers']['To'][0] === $testData['to']) {
                $emailFound = true;
                foreach($testData['string_to_check'] as $string) {
                    $this->assertStringContainsString($string, $email['Content']['Body']);
                }
                break;
            }
        }

        $this->assertTrue($emailFound, 'Test email not found in MailHog');
    }

    /**
     * Removes the specified properties from the given data array.
     *
     * @param array $data The data array from which to remove the properties.
     * @param array $properties The properties to be removed from the data array.
     * @return array
     */
    protected function removeProperty(array &$data, $properties) {
        if (is_array($data)) {
            foreach ($data as &$value) {
                if (is_array($value)) {
                    $this->removeProperty($value, $properties);
                }
            }
            foreach ($properties as $property) {
                unset($data[$property]);
            }
        }
    }
}
