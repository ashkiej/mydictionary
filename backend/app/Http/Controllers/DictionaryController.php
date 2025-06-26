<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DictionaryController extends Controller
{
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $word = $request->get('q');

        try {
            $response = Http::get("https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");

            if ($response->successful()) {
                $data = $response->json();
                $formatted = $this->formatDictionaryResponse($data);

                return response()->json([
                    'success' => true,
                    'data' => $formatted
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Word not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching word definition'
            ], 500);
        }
    }

    private function formatDictionaryResponse($data)
    {
        if (empty($data) || !is_array($data)) {
            return null;
        }

        $entry = $data[0];

        $phonetics = [];
        if (isset($entry['phonetics'])) {
            foreach ($entry['phonetics'] as $phonetic) {
                if (isset($phonetic['text']) || isset($phonetic['audio'])) {
                    $phonetics[] = [
                        'text' => $phonetic['text'] ?? '',
                        'audio' => $phonetic['audio'] ?? ''
                    ];
                }
            }
        }

        $definitions = [];
        $partOfSpeech = [];
        $exampleUsage = [];
        $synonyms = [];

        if (isset($entry['meanings'])) {
            foreach ($entry['meanings'] as $meaning) {
                $partOfSpeech[] = $meaning['partOfSpeech'] ?? '';

                if (isset($meaning['definitions'])) {
                    foreach ($meaning['definitions'] as $def) {
                        $definitions[] = [
                            'definition' => $def['definition'] ?? '',
                            'partOfSpeech' => $meaning['partOfSpeech'] ?? ''
                        ];

                        if (isset($def['example'])) {
                            $exampleUsage[] = $def['example'];
                        }

                        if (isset($def['synonyms'])) {
                            $synonyms = array_merge($synonyms, $def['synonyms']);
                        }
                    }
                }

                if (isset($meaning['synonyms'])) {
                    $synonyms = array_merge($synonyms, $meaning['synonyms']);
                }
            }
        }

        return [
            'word' => $entry['word'] ?? '',
            'phonetics' => $phonetics,
            'definitions' => $definitions,
            'part_of_speech' => array_unique($partOfSpeech),
            'example_usage' => $exampleUsage,
            'synonyms' => array_unique($synonyms)
        ];
    }
}
