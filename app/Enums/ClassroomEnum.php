<?php

namespace App\Enums;

enum ClassroomEnum: string
{
    case CLASS_7A = '7A';
    case CLASS_7B = '7B';
    case CLASS_7C = '7C';
    case CLASS_7D = '7D';
    case CLASS_7E = '7E';
    case CLASS_7F = '7F';
    case CLASS_7G = '7G';
    case CLASS_7H = '7H';
    case CLASS_7I = '7I';
    case CLASS_7J = '7J';
    case CLASS_7K = '7K';
    case CLASS_7L = '7L';
    case CLASS_7M = '7M';
    case CLASS_7N = '7N';
    case CLASS_7O = '7O';
    case CLASS_7P = '7P';
    case CLASS_7Q = '7Q';
    case CLASS_7R = '7R';
    case CLASS_7S = '7S';
    case CLASS_7T = '7T';
    case CLASS_7U = '7U';
    case CLASS_7V = '7V';
    case CLASS_7W = '7W';
    case CLASS_7X = '7X';
    case CLASS_7Y = '7Y';
    case CLASS_7Z = '7Z';

    case CLASS_8A = '8A';
    case CLASS_8B = '8B';
    case CLASS_8C = '8C';
    case CLASS_8D = '8D';
    case CLASS_8E = '8E';
    case CLASS_8F = '8F';
    case CLASS_8G = '8G';
    case CLASS_8H = '8H';
    case CLASS_8I = '8I';
    case CLASS_8J = '8J';
    case CLASS_8K = '8K';
    case CLASS_8L = '8L';
    case CLASS_8M = '8M';
    case CLASS_8N = '8N';
    case CLASS_8O = '8O';
    case CLASS_8P = '8P';
    case CLASS_8Q = '8Q';
    case CLASS_8R = '8R';
    case CLASS_8S = '8S';
    case CLASS_8T = '8T';
    case CLASS_8U = '8U';
    case CLASS_8V = '8V';
    case CLASS_8W = '8W';
    case CLASS_8X = '8X';
    case CLASS_8Y = '8Y';
    case CLASS_8Z = '8Z';

    case CLASS_9A = '9A';
    case CLASS_9B = '9B';
    case CLASS_9C = '9C';
    case CLASS_9D = '9D';
    case CLASS_9E = '9E';
    case CLASS_9F = '9F';
    case CLASS_9G = '9G';
    case CLASS_9H = '9H';
    case CLASS_9I = '9I';
    case CLASS_9J = '9J';
    case CLASS_9K = '9K';
    case CLASS_9L = '9L';
    case CLASS_9M = '9M';
    case CLASS_9N = '9N';
    case CLASS_9O = '9O';
    case CLASS_9P = '9P';
    case CLASS_9Q = '9Q';
    case CLASS_9R = '9R';
    case CLASS_9S = '9S';
    case CLASS_9T = '9T';
    case CLASS_9U = '9U';
    case CLASS_9V = '9V';
    case CLASS_9W = '9W';
    case CLASS_9X = '9X';
    case CLASS_9Y = '9Y';
    case CLASS_9Z = '9Z';

    public static function options()
    {
        return collect(self::cases())->map(fn($item) => [
            'value' => $item->value,
            'label' => $item->value,
        ])->values()->toArray();
    }
}
