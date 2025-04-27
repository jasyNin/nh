<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NeuronchikBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'is_active',
        'last_activity',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
        'settings' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analyzePost(string $content): array
    {
        // Проверяем, является ли это математическим выражением
        if ($this->isMathExpression($content)) {
            return [
                'is_math' => true,
                'expression' => $content,
                'is_question' => true
            ];
        }

        // Анализ тональности текста
        $tone = $this->analyzeTone($content);
        
        // Определение тематики
        $topic = $this->determineTopic($content);
        
        // Проверка, является ли пост вопросом
        $isQuestion = $this->isQuestion($content);

        // Анализ контекста
        $context = $this->analyzeContext($content);
        
        return [
            'tone' => $tone,
            'topic' => $topic,
            'is_question' => $isQuestion,
            'context' => $context,
            'is_math' => false
        ];
    }

    private function analyzeTone(string $content): string
    {
        // Расширенный анализ тональности
        $positiveWords = [
            'хорошо', 'отлично', 'прекрасно', 'рад', 'счастлив', 'люблю', 'нравится',
            'замечательно', 'великолепно', 'супер', 'класс', 'здорово', 'круто',
            'позитивно', 'потрясающе', 'восхитительно', 'прекрасно', 'чудесно'
        ];
        
        $negativeWords = [
            'плохо', 'ужасно', 'грустно', 'печально', 'разочарован', 'негативно',
            'отстой', 'ужас', 'кошмар', 'беда', 'проблема', 'сложно', 'трудно',
            'неприятно', 'неудобно', 'неправильно', 'ошибка', 'провал'
        ];
        
        $neutralWords = [
            'интересно', 'думаю', 'считаю', 'полагаю', 'возможно', 'наверное',
            'вероятно', 'похоже', 'кажется', 'видимо', 'очевидно', 'ясно'
        ];
        
        $content = mb_strtolower($content);
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
        
        foreach ($positiveWords as $word) {
            $positiveCount += substr_count($content, $word);
        }
        
        foreach ($negativeWords as $word) {
            $negativeCount += substr_count($content, $word);
        }

        foreach ($neutralWords as $word) {
            $neutralCount += substr_count($content, $word);
        }
        
        if ($positiveCount > $negativeCount && $positiveCount > $neutralCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount && $negativeCount > $neutralCount) {
            return 'negative';
        } elseif ($neutralCount > $positiveCount && $neutralCount > $negativeCount) {
            return 'neutral';
        }
        
        return 'neutral';
    }

    private function determineTopic(string $content): string
    {
        // Расширенное определение тематики
        $topics = [
            'weather' => [
                'погода', 'дождь', 'снег', 'солнце', 'температура', 'градус',
                'прогноз', 'климат', 'осадки', 'ветер', 'облака', 'туман',
                'жара', 'холод', 'мороз', 'тепло', 'прохладно'
            ],
            'technology' => [
                'компьютер', 'телефон', 'интернет', 'программа', 'техника',
                'гаджет', 'устройство', 'смартфон', 'ноутбук', 'планшет',
                'приложение', 'софт', 'хард', 'процессор', 'видеокарта',
                'оперативная память', 'жесткий диск', 'ssd', 'hdd'
            ],
            'programming' => [
                'программирование', 'код', 'разработка', 'программист', 'разработчик',
                'язык программирования', 'алгоритм', 'функция', 'метод', 'класс',
                'объект', 'интерфейс', 'абстракция', 'инкапсуляция', 'наследование',
                'полиморфизм', 'ооп', 'функциональное программирование', 'процедурное программирование',
                'база данных', 'sql', 'nosql', 'api', 'фреймворк', 'библиотека',
                'git', 'репозиторий', 'коммит', 'ветка', 'мерж', 'пулл реквест',
                'тестирование', 'юнит тест', 'интеграционный тест', 'отладка', 'дебаг',
                'оптимизация', 'производительность', 'безопасность', 'криптография',
                'машинное обучение', 'искусственный интеллект', 'нейронная сеть',
                'бэкенд', 'фронтенд', 'фуллстек', 'веб-разработка', 'мобильная разработка',
                'десктоп', 'сервер', 'клиент', 'протокол', 'http', 'https',
                'rest', 'graphql', 'websocket', 'микросервисы', 'контейнеризация',
                'docker', 'kubernetes', 'devops', 'ci/cd', 'автоматизация'
            ],
            'science' => [
                'наука', 'исследование', 'эксперимент', 'открытие', 'ученый',
                'физика', 'химия', 'биология', 'математика', 'астрономия',
                'космос', 'планета', 'звезда', 'галактика', 'атом',
                'молекула', 'клетка', 'ген', 'днк', 'эволюция'
            ],
            'health' => [
                'здоровье', 'болезнь', 'лечение', 'врач', 'медицина',
                'симптом', 'диагноз', 'лекарство', 'таблетка', 'укол',
                'операция', 'реабилитация', 'профилактика', 'иммунитет',
                'витамин', 'минерал', 'питание', 'диета', 'спорт'
            ],
            'education' => [
                'образование', 'учеба', 'школа', 'университет', 'колледж',
                'курс', 'лекция', 'семинар', 'экзамен', 'зачет',
                'диплом', 'степень', 'факультет', 'кафедра', 'преподаватель',
                'студент', 'ученик', 'класс', 'урок', 'задание'
            ],
            'facts' => [
                'интересно', 'факт', 'знаете', 'удивительно', 'невероятно',
                'потрясающе', 'уникально', 'редко', 'обычно', 'часто',
                'всегда', 'никогда', 'иногда', 'временами', 'порой'
            ],
            'general' => []
        ];
        
        $content = mb_strtolower($content);
        $maxMatches = 0;
        $detectedTopic = 'general';
        
        foreach ($topics as $topic => $keywords) {
            $matches = 0;
            foreach ($keywords as $keyword) {
                $matches += substr_count($content, $keyword);
            }
            if ($matches > $maxMatches) {
                $maxMatches = $matches;
                $detectedTopic = $topic;
            }
        }
        
        return $detectedTopic;
    }

    private function isQuestion(string $content): bool
    {
        // Расширенная проверка на вопрос
        $questionMarkers = [
            '?', 'что', 'как', 'почему', 'когда', 'где', 'кто', 'какой',
            'какая', 'какое', 'какие', 'чей', 'чья', 'чьё', 'чьи',
            'сколько', 'зачем', 'отчего', 'почему', 'каким образом',
            'в чем', 'в чем дело', 'в чем причина', 'как так', 'как же',
            'неужели', 'разве', 'ли', 'а', 'ведь', 'же'
        ];
        
        $content = mb_strtolower($content);
        
        // Проверяем наличие вопросительного знака
        if (strpos($content, '?') !== false) {
                return true;
        }
        
        // Проверяем наличие вопросительных слов
        foreach ($questionMarkers as $marker) {
            if (strpos($content, $marker) !== false) {
                // Проверяем, что это действительно вопрос, а не утверждение
                $words = explode(' ', $content);
                $markerIndex = array_search($marker, $words);
                
                if ($markerIndex !== false) {
                    // Проверяем, что после вопросительного слова идет глагол
                    $nextWord = $words[$markerIndex + 1] ?? '';
                    if (preg_match('/^(есть|быть|являться|называться|значить|означать|представлять|состоять|содержать|включать|иметь|обладать|характеризоваться|отличаться|выделяться|выделять|отмечать|подчеркивать|указывать|показывать|демонстрировать|иллюстрировать|описывать|характеризовать|определять|устанавливать|фиксировать|регистрировать|отмечать|подчеркивать|указывать|показывать|демонстрировать|иллюстрировать|описывать|характеризовать|определять|устанавливать|фиксировать|регистрировать)$/u', $nextWord)) {
                return true;
            }
        }
            }
        }

        return false;
    }

    private function analyzeContext(string $content): array
    {
        $context = [
            'time_reference' => $this->extractTimeReference($content),
            'location' => $this->extractLocation($content),
            'entities' => $this->extractEntities($content),
            'sentiment' => $this->analyzeSentiment($content),
            'complexity' => $this->analyzeComplexity($content)
        ];

        return $context;
    }

    private function extractTimeReference(string $content): ?string
    {
        $timePatterns = [
            'сегодня', 'завтра', 'вчера', 'сейчас', 'сейчас же',
            'немедленно', 'срочно', 'скоро', 'позже', 'потом',
            'в будущем', 'в прошлом', 'давно', 'недавно', 'только что',
            'уже', 'еще', 'пока', 'до сих пор', 'всегда'
        ];

        foreach ($timePatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                return $pattern;
            }
        }

        return null;
    }

    private function extractLocation(string $content): ?string
    {
        // Здесь можно добавить более сложную логику определения местоположения
        // Например, использовать базу данных городов или геокодинг
        return null;
    }

    private function extractEntities(string $content): array
    {
        $entities = [];
        
        // Извлекаем имена собственные
        preg_match_all('/[А-Я][а-я]+(?:\s+[А-Я][а-я]+)*/u', $content, $matches);
        if (!empty($matches[0])) {
            $entities['names'] = $matches[0];
        }
        
        // Извлекаем числа
        preg_match_all('/\d+(?:[.,]\d+)?/', $content, $matches);
        if (!empty($matches[0])) {
            $entities['numbers'] = $matches[0];
        }
        
        // Извлекаем даты
        preg_match_all('/\d{1,2}[.\/]\d{1,2}[.\/]\d{2,4}/', $content, $matches);
        if (!empty($matches[0])) {
            $entities['dates'] = $matches[0];
        }
        
        return $entities;
    }

    private function analyzeSentiment(string $content): array
    {
        $sentiment = [
            'positive' => 0,
            'negative' => 0,
            'neutral' => 0
        ];
        
        // Если контент пустой, возвращаем нейтральный сентимент
        if (empty($content)) {
            $sentiment['neutral'] = 1;
            return $sentiment;
        }
        
        // Анализ эмоциональных слов
        $positiveWords = ['хорошо', 'отлично', 'прекрасно', 'рад', 'счастлив'];
        $negativeWords = ['плохо', 'ужасно', 'грустно', 'печально', 'разочарован'];
        
        $content = mb_strtolower($content);
        
        foreach ($positiveWords as $word) {
            $sentiment['positive'] += substr_count($content, $word);
        }
        
        foreach ($negativeWords as $word) {
            $sentiment['negative'] += substr_count($content, $word);
        }
        
        // Нормализация значений
        $total = array_sum($sentiment);
        if ($total > 0) {
            foreach ($sentiment as $key => $value) {
                $sentiment[$key] = $value / $total;
            }
        } else {
            // Если не найдено ни одного эмоционального слова, считаем текст нейтральным
            $sentiment['neutral'] = 1;
        }
        
        return $sentiment;
    }

    private function analyzeComplexity(string $content): string
    {
        $words = str_word_count($content, 1, 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя');
        $sentences = preg_split('/[.!?]+/', $content);
        
        // Проверяем, что у нас есть слова и предложения
        if (empty($words) || empty($sentences)) {
            return 'simple';
        }
        
        $avgWordLength = array_sum(array_map('mb_strlen', $words)) / count($words);
        $avgSentenceLength = count($words) / count($sentences);
        
        if ($avgWordLength > 8 && $avgSentenceLength > 15) {
            return 'complex';
        } elseif ($avgWordLength > 6 && $avgSentenceLength > 10) {
            return 'medium';
        } else {
            return 'simple';
        }
    }

    private function isMathExpression(string $content): bool
    {
        // Удаляем все пробелы
        $content = str_replace(' ', '', $content);
        
        // Проверяем, содержит ли строка математические операторы
        if (preg_match('/[\+\-\*\/\=\?]/', $content)) {
            // Проверяем, что это действительно математическое выражение
            // а не просто текст с математическими символами
            $parts = preg_split('/[\+\-\*\/\=\?]/', $content);
            foreach ($parts as $part) {
                if (!empty($part) && !is_numeric($part)) {
        return false;
    }
            }
                return true;
        }
        
        return false;
    }

    private function solveMathExpression(string $expression): string
    {
        // Удаляем все пробелы и знак вопроса
        $expression = str_replace([' ', '?'], '', $expression);
        
        // Разбиваем выражение на части
        $parts = preg_split('/([\+\-\*\/\=])/', $expression, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        // Логируем части выражения для отладки
        \Log::info('Math expression parts:', $parts);
        
        // Если это выражение вида "2 + 2 = ?"
        if (count($parts) === 4 && $parts[1] === '+' && $parts[2] === '=' && $parts[3] === '?') {
            $result = (int)$parts[0] + (int)$parts[1];
            return "Результат выражения {$parts[0]} + {$parts[1]} равен {$result}";
        }
        
        // Если это выражение вида "2 + 2 ="
        if (count($parts) === 3 && $parts[1] === '+' && $parts[2] === '=') {
            $result = (int)$parts[0] + (int)$parts[1];
            return "Результат выражения {$parts[0]} + {$parts[1]} равен {$result}";
        }
        
        // Если это выражение вида "2 + 2"
        if (count($parts) === 3 && $parts[1] === '+') {
            $result = (int)$parts[0] + (int)$parts[2];
            return "Результат выражения {$parts[0]} + {$parts[2]} равен {$result}";
        }
        
        // Если это выражение вида "2 + 2 = ?"
        if (count($parts) === 3 && $parts[1] === '+' && $parts[2] === '?') {
            $result = (int)$parts[0] + (int)$parts[1];
            return "Результат выражения {$parts[0]} + {$parts[1]} равен {$result}";
        }
        
        // Для более сложных выражений можно добавить дополнительную логику
        
        return "Извините, я пока не могу решить такое сложное выражение";
    }

    public function generateResponse(array $analysis): string
    {
        // Если это математическое выражение
        if ($analysis['is_math'] ?? false) {
            return $this->solveMathExpression($analysis['expression']);
        }

        // Если это не вопрос, не генерируем ответ
        if (!$analysis['is_question']) {
            return '';
        }

        $responses = [
            'weather' => [
                'positive' => [
                    'Отличная погода сегодня! Надеюсь, вы сможете ей насладиться.',
                    'Прекрасная погода! Идеальное время для прогулки.',
                    'Замечательная погода! Не забудьте сделать красивые фотографии.'
                ],
                'negative' => [
                    'Да, погода не очень. Но скоро всё наладится!',
                    'Понимаю ваше разочарование. Погода действительно не радует.',
                    'Не переживайте, плохая погода не вечна. Скоро будет лучше!'
                ],
                'neutral' => [
                    'Интересно узнать ваше мнение о погоде.',
                    'Погода действительно переменчива в это время года.',
                    'Да, погода может быть непредсказуемой.'
                ]
            ],
            'technology' => [
                'positive' => [
                    'Рад, что вам нравится эта технология!',
                    'Отличный выбор! Эта технология действительно впечатляет.',
                    'Здорово, что вы интересуетесь технологиями!'
                ],
                'negative' => [
                    'Понимаю ваше разочарование. Технологии не всегда идеальны.',
                    'Да, у этой технологии есть свои недостатки.',
                    'Технологии развиваются, и эти проблемы скоро будут решены.'
                ],
                'neutral' => [
                    'Технологии развиваются очень быстро, не правда ли?',
                    'Интересная тема для обсуждения.',
                    'Технологии действительно меняют нашу жизнь.'
                ]
            ],
            'programming' => [
                'positive' => [
                    'Отлично, что вы интересуетесь программированием! Для начала изучения ООП рекомендую начать с базовых концепций: классы, объекты, наследование и инкапсуляция. Попробуйте написать простой класс, например, класс "Автомобиль" с базовыми свойствами и методами.',
                    'Рад вашему интересу к программированию! Для понимания ООП начните с изучения основных принципов: абстракция, инкапсуляция, наследование и полиморфизм. Практикуйтесь на простых примерах, постепенно усложняя задачи.',
                    'Здорово, что вы решили изучить ООП! Начните с создания простых классов и объектов. Например, создайте класс "Студент" с методами для работы с оценками и посещаемостью.'
                ],
                'negative' => [
                    'Понимаю ваши сложности с ООП. Начните с самых основ: создайте простой класс с одним свойством и методом. Постепенно добавляйте новые возможности и изучайте принципы наследования. Рекомендую начать с изучения классов и объектов на примере реальных предметов.',
                    'Не переживайте, ООП может быть сложным в начале. Попробуйте начать с изучения классов и объектов на примере реальных предметов. Например, создайте класс "Книга" с методами для работы с содержанием. Постепенно переходите к более сложным концепциям.',
                    'Да, ООП может быть непростым. Начните с изучения базовых концепций через практические примеры. Создайте простой проект, например, систему управления библиотекой. Это поможет вам лучше понять принципы ООП на практике.'
                ],
                'neutral' => [
                    'Для изучения ООП рекомендую начать с базовых концепций: классы, объекты, методы. Создайте простой класс, например, "Пользователь" с основными свойствами и методами. Постепенно изучайте принципы наследования и полиморфизма.',
                    'Начните изучение ООП с создания простых классов и объектов. Попробуйте реализовать базовые принципы на примере реальных предметов. Например, создайте систему управления задачами с использованием ООП.',
                    'Для понимания ООП важно начать с практики. Создайте простой проект, например, систему управления задачами, используя основные принципы ООП. Это поможет вам лучше понять, как применять ООП в реальных проектах.'
                ]
            ],
            'science' => [
                'positive' => [
                    'Отличный вопрос! Наука действительно увлекательна.',
                    'Рад вашему интересу к науке!',
                    'Прекрасный вопрос для научного обсуждения.'
                ],
                'negative' => [
                    'Понимаю ваши сомнения. Наука не всегда может дать простые ответы.',
                    'Да, это сложный научный вопрос.',
                    'Наука постоянно развивается, и ответы могут меняться.'
                ],
                'neutral' => [
                    'Интересный научный вопрос.',
                    'Наука пытается найти ответы на многие вопросы.',
                    'Это хороший вопрос для научного исследования.'
                ]
            ],
            'health' => [
                'positive' => [
                    'Рад, что вы заботитесь о своем здоровье!',
                    'Отличный вопрос о здоровье!',
                    'Здоровье - это самое главное!'
                ],
                'negative' => [
                    'Понимаю ваше беспокойство о здоровье.',
                    'Здоровье требует внимания и заботы.',
                    'Не переживайте, медицина постоянно развивается.'
                ],
                'neutral' => [
                    'Важно следить за своим здоровьем.',
                    'Здоровье требует регулярного внимания.',
                    'Хороший вопрос о здоровье.'
                ]
            ],
            'education' => [
                'positive' => [
                    'Отлично, что вы интересуетесь образованием!',
                    'Образование - это ключ к успеху!',
                    'Рад вашему интересу к обучению!'
                ],
                'negative' => [
                    'Понимаю ваши сложности с обучением.',
                    'Образование может быть сложным, но оно того стоит.',
                    'Не переживайте, все трудности преодолимы.'
                ],
                'neutral' => [
                    'Образование - важная часть нашей жизни.',
                    'Интересный вопрос об образовании.',
                    'Обучение - это постоянный процесс.'
                ]
            ],
            'facts' => [
                'positive' => [
                    'Очень интересный факт! Спасибо, что поделились.',
                    'Потрясающая информация!',
                    'Удивительный факт!'
                ],
                'negative' => [
                    'Да, это действительно удивительно.',
                    'Интересная информация, хотя и не очень приятная.',
                    'Факты могут быть разными.'
                ],
                'neutral' => [
                    'Интересная информация, спасибо!',
                    'Любопытный факт.',
                    'Спасибо за информацию.'
                ]
            ],
            'general' => [
                'positive' => [
                    'Рад, что вы поделились этим!',
                    'Отличный вопрос!',
                    'Спасибо за интересную тему!'
                ],
                'negative' => [
                    'Понимаю ваши чувства.',
                    'Спасибо, что поделились.',
                    'Интересная точка зрения.'
                ],
                'neutral' => [
                    'Спасибо за ваш вопрос!',
                    'Интересная тема для обсуждения.',
                    'Спасибо за информацию.'
                ]
            ]
        ];

        $topic = $analysis['topic'];
        $tone = $analysis['tone'];
        
        // Выбираем случайный ответ из соответствующей категории
        $possibleResponses = $responses[$topic][$tone] ?? $responses['general'][$tone];
        return $possibleResponses[array_rand($possibleResponses)];
    }
} 