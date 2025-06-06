<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            // Технологии и программирование
            ['name' => 'веб-разработка', 'description' => 'Разработка веб-сайтов и веб-приложений'],
            ['name' => 'мобильные приложения', 'description' => 'Разработка приложений для мобильных устройств'],
            ['name' => 'искусственный интеллект', 'description' => 'ИИ, машинное обучение и нейронные сети'],
            
            // Наука и образование
            ['name' => 'физика', 'description' => 'Физика и её законы'],
            ['name' => 'химия', 'description' => 'Химия и химические процессы'],
            ['name' => 'биология', 'description' => 'Биология и живые организмы'],
            ['name' => 'математика', 'description' => 'Математика и её разделы'],
            
            // Культура и искусство
            ['name' => 'литература', 'description' => 'Книги, писатели и литературные произведения'],
            ['name' => 'живопись', 'description' => 'Изобразительное искусство и живопись'],
            ['name' => 'музыка', 'description' => 'Музыка, исполнители и инструменты'],
            ['name' => 'кино', 'description' => 'Фильмы, режиссёры и актёры'],
            
            // Спорт и здоровье
            ['name' => 'футбол', 'description' => 'Футбол и футбольные команды'],
            ['name' => 'йога', 'description' => 'Йога и медитация'],
            ['name' => 'здоровое питание', 'description' => 'Правильное питание и диеты'],
            
            // Путешествия и география
            ['name' => 'путешествия', 'description' => 'Путешествия по миру'],
            ['name' => 'география', 'description' => 'География и страны мира'],
            
            // Бизнес и финансы
            ['name' => 'инвестиции', 'description' => 'Инвестиции и финансовые рынки'],
            ['name' => 'предпринимательство', 'description' => 'Бизнес и стартапы'],
            
            // Дом и быт
            ['name' => 'интерьер', 'description' => 'Дизайн интерьера и ремонт'],
            ['name' => 'садоводство', 'description' => 'Садоводство и огород'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                [
                    'description' => $tag['description'],
                    'slug' => Str::slug($tag['name']),
                ]
            );
        }
    }
} 