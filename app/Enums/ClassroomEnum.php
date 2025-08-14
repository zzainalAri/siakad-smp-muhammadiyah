<?php

namespace App\Enums;

enum ClassroomEnum: string
{
    case CLASS_7A = 'Kelas 7A';
    case CLASS_7B = 'Kelas 7B';
    case CLASS_7C = 'Kelas 7C';
    case CLASS_7D = 'Kelas 7D';
    case CLASS_7E = 'Kelas 7E';
    case CLASS_7F = 'Kelas 7F';
    case CLASS_7G = 'Kelas 7G';
    case CLASS_7H = 'Kelas 7H';
    case CLASS_7I = 'Kelas 7I';
    case CLASS_7J = 'Kelas 7J';
    case CLASS_7K = 'Kelas 7K';
    case CLASS_7L = 'Kelas 7L';
    case CLASS_7M = 'Kelas 7M';
    case CLASS_7N = 'Kelas 7N';
    case CLASS_7O = 'Kelas 7O';
    case CLASS_7P = 'Kelas 7P';
    case CLASS_7Q = 'Kelas 7Q';
    case CLASS_7R = 'Kelas 7R';
    case CLASS_7S = 'Kelas 7S';
    case CLASS_7T = 'Kelas 7T';
    case CLASS_7U = 'Kelas 7U';
    case CLASS_7V = 'Kelas 7V';
    case CLASS_7W = 'Kelas 7W';
    case CLASS_7X = 'Kelas 7X';
    case CLASS_7Y = 'Kelas 7Y';
    case CLASS_7Z = 'Kelas 7Z';

    case CLASS_8A = 'Kelas 8A';
    case CLASS_8B = 'Kelas 8B';
    case CLASS_8C = 'Kelas 8C';
    case CLASS_8D = 'Kelas 8D';
    case CLASS_8E = 'Kelas 8E';
    case CLASS_8F = 'Kelas 8F';
    case CLASS_8G = 'Kelas 8G';
    case CLASS_8H = 'Kelas 8H';
    case CLASS_8I = 'Kelas 8I';
    case CLASS_8J = 'Kelas 8J';
    case CLASS_8K = 'Kelas 8K';
    case CLASS_8L = 'Kelas 8L';
    case CLASS_8M = 'Kelas 8M';
    case CLASS_8N = 'Kelas 8N';
    case CLASS_8O = 'Kelas 8O';
    case CLASS_8P = 'Kelas 8P';
    case CLASS_8Q = 'Kelas 8Q';
    case CLASS_8R = 'Kelas 8R';
    case CLASS_8S = 'Kelas 8S';
    case CLASS_8T = 'Kelas 8T';
    case CLASS_8U = 'Kelas 8U';
    case CLASS_8V = 'Kelas 8V';
    case CLASS_8W = 'Kelas 8W';
    case CLASS_8X = 'Kelas 8X';
    case CLASS_8Y = 'Kelas 8Y';
    case CLASS_8Z = 'Kelas 8Z';

    case CLASS_9A = 'Kelas 9A';
    case CLASS_9B = 'Kelas 9B';
    case CLASS_9C = 'Kelas 9C';
    case CLASS_9D = 'Kelas 9D';
    case CLASS_9E = 'Kelas 9E';
    case CLASS_9F = 'Kelas 9F';
    case CLASS_9G = 'Kelas 9G';
    case CLASS_9H = 'Kelas 9H';
    case CLASS_9I = 'Kelas 9I';
    case CLASS_9J = 'Kelas 9J';
    case CLASS_9K = 'Kelas 9K';
    case CLASS_9L = 'Kelas 9L';
    case CLASS_9M = 'Kelas 9M';
    case CLASS_9N = 'Kelas 9N';
    case CLASS_9O = 'Kelas 9O';
    case CLASS_9P = 'Kelas 9P';
    case CLASS_9Q = 'Kelas 9Q';
    case CLASS_9R = 'Kelas 9R';
    case CLASS_9S = 'Kelas 9S';
    case CLASS_9T = 'Kelas 9T';
    case CLASS_9U = 'Kelas 9U';
    case CLASS_9V = 'Kelas 9V';
    case CLASS_9W = 'Kelas 9W';
    case CLASS_9X = 'Kelas 9X';
    case CLASS_9Y = 'Kelas 9Y';
    case CLASS_9Z = 'Kelas 9Z';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
