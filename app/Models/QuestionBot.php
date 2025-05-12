<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Dataset\ArrayDataset;
use Phpml\Preprocessing\Normalizer;

class QuestionBot extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'is_active',
        'settings'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'last_activity' => 'datetime'
    ];

    // Константы для типов вопросов
    const TYPE_MATH = 'math';
    const TYPE_PROGRAMMING = 'programming';
    const TYPE_LOGICAL = 'logical';
    const TYPE_GENERAL = 'general';

    // Известные логические задачи и их ответы
    const LOGICAL_PUZZLES = [
        'месяц.*28.*день' => [
            'answer' => "Все 12 месяцев в году имеют 28 дней! Это классическая логическая задача, которая часто вводит в заблуждение.",
            'explanation' => [
                "В каждом месяце есть 28-е число",
                "В феврале 28 дней (в високосный год - 29)",
                "В остальных месяцах больше 28 дней"
            ]
        ],
        'часов.*день' => [
            'answer' => "В сутках 24 часа, но если вопрос о том, сколько раз часовая стрелка делает полный оборот за день, то ответ - 2 раза!",
            'explanation' => [
                "Часовая стрелка делает полный оборот за 12 часов",
                "В сутках 24 часа",
                "Значит, стрелка делает 2 полных оборота за сутки"
            ]
        ],
        'минут.*час' => [
            'answer' => "В часе 60 минут, но если вопрос о том, сколько раз минутная стрелка обгоняет часовую за час, то ответ - 11 раз!",
            'explanation' => [
                "Минутная стрелка делает полный оборот за 60 минут",
                "За это время часовая стрелка проходит 1/12 часть круга",
                "Значит, минутная стрелка обгоняет часовую 11 раз за час"
            ]
        ],
        'собака.*скорость.*сковородка' => [
            'answer' => "Собака должна двигаться со скоростью звука (около 343 м/с в воздухе), чтобы не слышать звона сковородки!",
            'explanation' => [
                "Звук распространяется в воздухе со скоростью около 343 м/с",
                "Если собака движется со скоростью звука, звуковые волны не могут её догнать",
                "Это похоже на эффект Доплера, но в данном случае объект движется быстрее звука",
                "Конечно, это теоретический ответ, так как собака физически не может развить такую скорость"
            ]
        ]
    ];

    private $classifier;
    private $vectorizer;
    private $normalizer;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->initializeML();
    }

    private function initializeML()
    {
        // Инициализируем векторизатор для преобразования текста в числовые признаки
        $this->vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        
        // Инициализируем нормализатор
        $this->normalizer = new Normalizer();

        // Подготовка обучающих данных
        $samples = $this->getTrainingSamples();
        $labels = $this->getTrainingLabels();

        // Векторизация обучающих данных
        $this->vectorizer->fit($samples);
        $this->vectorizer->transform($samples);

        // Нормализация данных
        $this->normalizer->transform($samples);

        // Создаем и обучаем классификатор
        $this->classifier = new SVC(
            Kernel::RBF, // Радиальная базисная функция
            3.0,        // C параметр
            3,          // degree
            null,       // gamma
            0.001,      // coef0
            0.1,        // tolerance
            100,        // cache size
            true,       // shrinking
            true        // probability estimates
        );

        // Обучаем классификатор
        $dataset = new ArrayDataset($samples, $labels);
        $this->classifier->train($samples, $labels);
    }

    private function getTrainingSamples(): array
    {
        return [
            // Математические вопросы (10 примеров)
            "сколько будет 5 + 3",
            "вычисли 10 * 7",
            "реши уравнение 2x + 5 = 15",
            "найди корень уравнения x^2 = 16",
            "посчитай 15 - 8",
            "реши пример 20 / 4",
            "вычисли 3 в квадрате",
            "найди площадь круга",
            "реши систему уравнений",
            "вычисли интеграл",
            
            // Программирование (10 примеров)
            "как написать функцию на php",
            "что такое solid принципы",
            "как работает база данных",
            "объясни паттерн singleton",
            "как оптимизировать sql запрос",
            "как использовать git",
            "что такое docker",
            "как настроить nginx",
            "как писать unit тесты",
            "как использовать composer",
            
            // Логические задачи (10 примеров)
            "сколько месяцев в году имеют 28 дней",
            "сколько раз часовая стрелка делает полный оборот за день",
            "сколько раз минутная стрелка обгоняет часовую за час",
            "какой месяц короче всех",
            "почему в феврале 28 дней",
            "как разделить торт на 8 частей тремя разрезами",
            "как перевезти волка козу и капусту",
            "как измерить 4 литра воды",
            "как определить фальшивую монету",
            "как переправить всех через реку",
            
            // Общие вопросы и приветствия (10 примеров)
            "привет",
            "здравствуй",
            "добрый день",
            "доброе утро",
            "добрый вечер",
            "как дела",
            "как ты",
            "как жизнь",
            "что нового",
            "как поживаешь"
        ];
    }

    private function getTrainingLabels(): array
    {
        return [
            // Математические вопросы (10 меток)
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            self::TYPE_MATH,
            
            // Программирование (10 меток)
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            self::TYPE_PROGRAMMING,
            
            // Логические задачи (10 меток)
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            self::TYPE_LOGICAL,
            
            // Общие вопросы и приветствия (10 меток)
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL,
            self::TYPE_GENERAL
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generateAnswer(string $question): string
    {
        try {
            // Анализируем тип вопроса с помощью ML
            $type = $this->analyzeQuestionType($question);
            
            // Логируем тип вопроса
            Log::info("Question type detected", [
                'question' => $question,
                'type' => $type
            ]);
            
            // Генерируем ответ в зависимости от типа
            $answer = match($type) {
                self::TYPE_MATH => $this->generateMathAnswer($question),
                self::TYPE_PROGRAMMING => $this->generateProgrammingAnswer($question),
                self::TYPE_LOGICAL => $this->generateLogicalAnswer($question),
                default => $this->generateGeneralAnswer($question)
            };

            // Логируем ответ
            Log::info("Answer generated", [
                'question' => $question,
                'answer' => $answer
            ]);

            return $answer;
        } catch (\Exception $e) {
            Log::error("Error generating answer", [
                'question' => $question,
                'error' => $e->getMessage()
            ]);
            
            return "Извините, произошла ошибка при обработке вашего вопроса. Пожалуйста, попробуйте переформулировать его.";
        }
    }

    private function analyzeQuestionType(string $question): string
    {
        // Сначала проверяем на общие вопросы и приветствия
        $question = Str::lower($question);
        
        // Проверяем на приветствия и общие вопросы
        if (preg_match('/^(привет|здравствуй|добрый день|доброе утро|добрый вечер|хай|хеллоу|hi|hello)$/i', $question)) {
            return self::TYPE_GENERAL;
        }
        
        if (preg_match('/^(как дела|как ты|как жизнь|что нового|как поживаешь)$/i', $question)) {
            return self::TYPE_GENERAL;
        }
        
        if (preg_match('/^(кто ты|что ты умеешь|расскажи о себе|что ты можешь|как тебя зовут)$/i', $question)) {
            return self::TYPE_GENERAL;
        }
        
        // Проверяем на математические вопросы (расширенный паттерн)
        if (preg_match('/(\d+\s*[\+\-\*\/]\s*\d+)|(\d+\s*%\s*от\s*\d+)|(найти\s*\d+\s*%\s*от\s*\d+)|(уравнение|решить|вычислить|посчитать|интеграл|производная|квадрат|корень|процент|скидк)/i', $question)) {
            return self::TYPE_MATH;
        }
        
        // Проверяем на вопросы по программированию
        if (preg_match('/(php|javascript|python|java|c\+\+|код|программирование|ошибка|баг|фреймворк|laravel|vue|react|angular|верстка|css|html|git|docker|nginx|composer)/i', $question)) {
            return self::TYPE_PROGRAMMING;
        }
        
        // Проверяем на логические задачи
        if (preg_match('/(загадка|головоломка|логика|разделить|перевезти|измерить|определить|переправить)/i', $question)) {
            return self::TYPE_LOGICAL;
        }
        
        // Если есть числа, считаем это математической задачей
        if (preg_match('/\d+/', $question)) {
            return self::TYPE_MATH;
        }
        
        // Если не определили тип, используем ML классификатор
        $samples = [$question];
        $this->vectorizer->transform($samples);
        $this->normalizer->transform($samples);
        return $this->classifier->predict($samples[0]);
    }

    private function generateLogicalAnswer(string $question): string
    {
        $question = Str::lower($question);
        
        // Ищем известную логическую задачу
        foreach (self::LOGICAL_PUZZLES as $pattern => $data) {
            if (preg_match("/{$pattern}/i", $question)) {
                return $this->formatLogicalAnswer($data['answer'], $data['explanation']);
            }
        }

        // Если не нашли известную задачу, генерируем общий ответ
        return $this->generateGeneralLogicalAnswer($question);
    }

    private function formatLogicalAnswer(string $answer, array $explanation): string
    {
        $response = $answer . "\n\nОбъяснение:\n";
        foreach ($explanation as $index => $step) {
            $response .= "\n" . ($index + 1) . ". " . $step;
        }
        return $response;
    }

    private function generateGeneralLogicalAnswer(string $question): string
    {
        $keywords = $this->extractKeywords($question);
        
        return "Это интересная логическая задача! Давайте разберем её пошагово:
        \n\n1. Проанализируем условие: " . $question . "
        \n2. Выделим ключевые элементы: " . implode(', ', $keywords) . "
        \n3. Рассмотрим все возможные варианты
        \n4. Проверим каждый вариант на соответствие условию
        \n\nХотите, чтобы я помог вам разобрать эту задачу подробнее?";
    }

    private function generateMathAnswer(string $question): string
    {
        $question = trim(strtolower($question));
        
        // Handle percentage problems first
        if (preg_match('/(найти|посчитать|вычислить|сколько)?\s*(\d+)\s*%\s*(от)?\s*(\d+)/i', $question, $matches)) {
            $percentage = floatval($matches[2]);
            $base = floatval($matches[4]);
            $result = ($base * $percentage) / 100;
            
            return "Находим процент от числа:\n\n" .
                   "1. Исходное число: {$base}\n" .
                   "2. Нужно найти: {$percentage}%\n" .
                   "3. Вычисляем: {$base} × {$percentage}% = {$result}\n\n" .
                   "Ответ: {$result}";
        }
        
        // Handle discount problems
        if (preg_match('/(скидк|процент|%)/i', $question)) {
            return $this->solvePercentageProblem($question);
        }
        
        // Handle arithmetic expressions with multiple operations
        if (preg_match('/[\d\s\+\-\*\/\(\)]+/', $question, $matches)) {
            $expression = $matches[0];
            try {
                // Remove any unsafe characters and evaluate
                $expression = preg_replace('/[^0-9\+\-\*\/\(\)\s\.]/', '', $expression);
                $steps = $this->solveArithmeticExpression($expression);
                return $steps;
            } catch (\Exception $e) {
                return "Извините, не могу вычислить это выражение. Пожалуйста, проверьте правильность записи.";
            }
        }

        // Handle divisibility problems
        if (preg_match('/(делит|кратн)/i', $question)) {
            return $this->solveDivisibilityProblem($question);
        }

        // Handle word problems
        if (strlen($question) > 50 && preg_match('/(задач|решить|найти)/i', $question)) {
            return $this->solveWordProblem($question);
        }

        return "Я вижу, что это математический вопрос. Пожалуйста, уточните задачу. Я могу помочь с:\n\n" .
               "1. Арифметическими вычислениями (например: 2 + 4 * 3)\n" .
               "2. Задачами на проценты (например: найти 15% от 200)\n" .
               "3. Задачами на делимость чисел\n" .
               "4. Текстовыми задачами\n\n" .
               "Просто сформулируйте вашу задачу более конкретно.";
    }

    private function solveArithmeticExpression(string $expression): string
    {
        // Remove extra spaces
        $expression = preg_replace('/\s+/', '', $expression);
        
        // Parse and solve the expression
        $steps = [];
        $steps[] = "Исходное выражение: " . $expression;

        // Handle parentheses first
        while (strpos($expression, '(') !== false) {
            preg_match('/\(([^()]+)\)/', $expression, $matches);
            $subExpr = $matches[1];
            $subResult = $this->evaluateSimpleExpression($subExpr);
            $expression = str_replace("({$subExpr})", $subResult, $expression);
            $steps[] = "Вычисляем выражение в скобках: ({$subExpr}) = {$subResult}";
            $steps[] = "Получаем: " . $expression;
        }

        // Evaluate the final expression
        $result = $this->evaluateSimpleExpression($expression);
        $steps[] = "Окончательный результат: " . $result;

        return implode("\n", $steps);
    }

    private function evaluateSimpleExpression(string $expr): float
    {
        // Handle multiplication and division first
        while (preg_match('/(-?\d+\.?\d*[\*\/]-?\d+\.?\d*)/', $expr, $matches)) {
            $subExpr = $matches[1];
            if (strpos($subExpr, '*') !== false) {
                $nums = explode('*', $subExpr);
                $result = floatval($nums[0]) * floatval($nums[1]);
            } else {
                $nums = explode('/', $subExpr);
                if (floatval($nums[1]) == 0) {
                    throw new \Exception("Division by zero");
                }
                $result = floatval($nums[0]) / floatval($nums[1]);
            }
            $expr = str_replace($subExpr, $result, $expr);
        }

        // Handle addition and subtraction
        while (preg_match('/(-?\d+\.?\d*[\+\-]-?\d+\.?\d*)/', $expr, $matches)) {
            $subExpr = $matches[1];
            if (strpos($subExpr, '+') !== false) {
                $nums = explode('+', $subExpr);
                $result = floatval($nums[0]) + floatval($nums[1]);
            } else {
                $nums = explode('-', $subExpr);
                $result = floatval($nums[0]) - floatval($nums[1]);
            }
            $expr = str_replace($subExpr, $result, $expr);
        }

        return floatval($expr);
    }

    private function solvePercentageProblem(string $question): string
    {
        // Extract numbers from the question
        preg_match_all('/\d+(?:\.\d+)?/', $question, $matches);
        $numbers = $matches[0];

        if (count($numbers) >= 2) {
            $base = floatval($numbers[0]);
            $percentage = floatval($numbers[1]);

            // Determine if we're finding the percentage of a number or finding what percentage one number is of another
            if (strpos(strtolower($question), 'скидк') !== false) {
                $discountAmount = ($base * $percentage) / 100;
                $finalPrice = $base - $discountAmount;
                
                return "Решаем задачу со скидкой:\n\n" .
                       "1. Исходная цена: {$base}\n" .
                       "2. Процент скидки: {$percentage}%\n" .
                       "3. Вычисляем сумму скидки: {$base} × {$percentage}% = {$discountAmount}\n" .
                       "4. Вычисляем конечную цену: {$base} - {$discountAmount} = {$finalPrice}\n\n" .
                       "Ответ: {$finalPrice}";
            } else {
                $result = ($base * $percentage) / 100;
                
                return "Находим процент от числа:\n\n" .
                       "1. Исходное число: {$base}\n" .
                       "2. Нужно найти: {$percentage}%\n" .
                       "3. Вычисляем: {$base} × {$percentage}% = {$result}\n\n" .
                       "Ответ: {$result}";
            }
        }

        return "Для решения задачи на проценты мне нужны два числа:\n" .
               "1. Исходное число\n" .
               "2. Процент, который нужно найти\n\n" .
               "Например: 'Найти 15% от 200' или 'Какова цена товара за 1000 рублей со скидкой 20%?'";
    }

    private function solveDivisibilityProblem(string $question): string
    {
        // Extract numbers from the question
        preg_match_all('/\d+/', $question, $matches);
        $numbers = $matches[0];

        if (count($numbers) >= 2) {
            $num1 = intval($numbers[0]);
            $num2 = intval($numbers[1]);

            $isDivisible = $num1 % $num2 === 0;
            $quotient = intval($num1 / $num2);
            $remainder = $num1 % $num2;

            $steps = "Проверяем делимость чисел:\n\n" .
                    "1. Первое число: {$num1}\n" .
                    "2. Второе число: {$num2}\n" .
                    "3. Делим {$num1} на {$num2}:\n" .
                    "   - Частное: {$quotient}\n" .
                    "   - Остаток: {$remainder}\n\n";

            if ($isDivisible) {
                $steps .= "Вывод: {$num1} делится на {$num2} без остатка.";
            } else {
                $steps .= "Вывод: {$num1} не делится на {$num2} без остатка. Остаток от деления: {$remainder}";
            }

            return $steps;
        }

        return "Для проверки делимости мне нужны два числа. Например:\n" .
               "'Делится ли 15 на 3?' или 'Проверить кратность 100 и 25'";
    }

    private function solveWordProblem(string $question): string
    {
        // Extract numbers and keywords from the question
        preg_match_all('/\d+/', $question, $matches);
        $numbers = $matches[0];
        
        $keywords = [
            'сложить' => '+',
            'прибавить' => '+',
            'добавить' => '+',
            'плюс' => '+',
            'вычесть' => '-',
            'отнять' => '-',
            'минус' => '-',
            'умножить' => '*',
            'помножить' => '*',
            'разделить' => '/',
            'поделить' => '/'
        ];

        $steps = "Разбираем текстовую задачу:\n\n" .
                 "1. Анализ условия:\n" .
                 "   - Дано: " . implode(', ', $numbers) . "\n";

        // Try to identify the operation
        $operation = null;
        foreach ($keywords as $word => $op) {
            if (strpos($question, $word) !== false) {
                $operation = $op;
                break;
            }
        }

        if ($operation && count($numbers) >= 2) {
            $num1 = floatval($numbers[0]);
            $num2 = floatval($numbers[1]);
            
            $steps .= "2. Определяем операцию: " . $operation . "\n";
            
            switch ($operation) {
                case '+':
                    $result = $num1 + $num2;
                    $steps .= "3. Складываем числа: {$num1} + {$num2} = {$result}";
                    break;
                case '-':
                    $result = $num1 - $num2;
                    $steps .= "3. Вычитаем числа: {$num1} - {$num2} = {$result}";
                    break;
                case '*':
                    $result = $num1 * $num2;
                    $steps .= "3. Умножаем числа: {$num1} × {$num2} = {$result}";
                    break;
                case '/':
                    if ($num2 != 0) {
                        $result = $num1 / $num2;
                        $steps .= "3. Делим числа: {$num1} ÷ {$num2} = {$result}";
                    } else {
                        return "В задаче обнаружено деление на ноль, что невозможно.";
                    }
                    break;
            }

            return $steps . "\n\nОтвет: " . $result;
        }

        return "Я пока не могу решить эту текстовую задачу. Пожалуйста, убедитесь, что:\n" .
               "1. В задаче есть все необходимые числа\n" .
               "2. Четко указано, какую операцию нужно выполнить\n" .
               "3. Задача сформулирована ясно и однозначно";
    }

    private function generateProgrammingAnswer(string $question): string
    {
        $responses = [
            "Я вижу, что у вас вопрос по программированию. Давайте разберем его подробно:\n\n" .
            "1. Сначала нужно понять суть проблемы\n" .
            "2. Затем определить оптимальный подход к решению\n" .
            "3. Написать код с учетом лучших практик\n" .
            "4. Протестировать решение\n\n" .
            "Можете уточнить, какой именно аспект программирования вас интересует? " .
            "Я могу помочь с алгоритмами, архитектурой, отладкой или оптимизацией кода.",

            "Для решения вашего вопроса по программированию, я рекомендую:\n\n" .
            "1. Четко определить требования\n" .
            "2. Выбрать подходящий алгоритм\n" .
            "3. Учесть возможные edge cases\n" .
            "4. Оптимизировать производительность\n\n" .
            "Какой язык программирования вы используете? " .
            "Я могу дать более конкретные рекомендации с учетом особенностей вашего языка.",

            "В программировании важно:\n\n" .
            "1. Писать чистый и поддерживаемый код\n" .
            "2. Следовать принципам SOLID\n" .
            "3. Использовать паттерны проектирования\n" .
            "4. Писать тесты\n\n" .
            "Что именно вы пытаетесь реализовать? " .
            "Я могу предложить несколько подходов к решению вашей задачи."
        ];

        return $responses[array_rand($responses)];
    }

    private function generateGeneralAnswer(string $question): string
    {
        // Приводим вопрос к нижнему регистру для сравнения
        $question = Str::lower($question);
        
        // Проверяем на приветствия
        if (preg_match('/^(привет|здравствуй|добрый день|доброе утро|добрый вечер|хай|хеллоу|hi|hello)$/i', $question)) {
            return "Привет! Как я могу вам помочь? Я могу ответить на вопросы по программированию, математике или помочь с логическими задачами.";
        }
        
        // Проверяем на вопросы "как дела"
        if (preg_match('/^(как дела|как ты|как жизнь|что нового|как поживаешь)$/i', $question)) {
            return "У меня всё хорошо, спасибо! Я готов помогать вам с вопросами. Что вас интересует?";
        }
        
        // Проверяем на вопросы о боте
        if (preg_match('/^(кто ты|что ты умеешь|расскажи о себе|что ты можешь)$/i', $question)) {
            return "Я Нейрончик - бот-помощник. Я могу:\n\n" .
                   "1. Отвечать на вопросы по программированию\n" .
                   "2. Решать математические задачи\n" .
                   "3. Помогать с логическими головоломками\n\n" .
                   "Просто задайте мне вопрос, и я постараюсь помочь!";
        }
        
        // Для остальных общих вопросов
        return "Спасибо за ваш вопрос! Чтобы я мог дать более точный и полезный ответ, " .
               "пожалуйста, уточните, что именно вас интересует? " .
               "Я могу помочь с математикой, программированием или ответить на другие вопросы.";
    }

    private function extractKeywords(string $text): array
    {
        // Удаляем знаки препинания и приводим к нижнему регистру
        $text = Str::lower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text));
        
        // Разбиваем на слова
        $words = explode(' ', $text);
        
        // Удаляем стоп-слова
        $stopWords = ['как', 'что', 'где', 'когда', 'почему', 'зачем', 'кто', 'который', 'это', 'для', 'на', 'в', 'с', 'по', 'от', 'до'];
        $words = array_diff($words, $stopWords);
        
        // Удаляем пустые значения и дубликаты
        $words = array_filter(array_unique($words));
        
        // Возвращаем до 5 ключевых слов
        return array_slice($words, 0, 5);
    }
} 