import React from 'react';

const AboutPage = () => {
    return (
        <div className="page-content">
            <h1>О проекте</h1>

            <section className="about-section">
                <h2>Наша миссия</h2>
                <p>
                    Мы стремимся создать платформу, где люди могут делиться знаниями, 
                    задавать вопросы и находить ответы. Наша цель - сделать процесс 
                    обучения и обмена опытом максимально удобным и эффективным.
                </p>
            </section>

            <section className="about-section">
                <h2>Что мы предлагаем</h2>
                <ul>
                    <li>Создание и публикация постов</li>
                    <li>Система вопросов и ответов</li>
                    <li>Рейтинговая система</li>
                    <li>Система тегов</li>
                    <li>Закладки</li>
                    <li>Уведомления</li>
                </ul>
            </section>

            <section className="about-section">
                <h2>Наша команда</h2>
                <div className="team-grid">
                    <div className="team-member">
                        <img src="/images/team/member1.jpg" alt="Член команды 1" />
                        <h3>Иван Иванов</h3>
                        <p>Основатель и CEO</p>
                    </div>
                    <div className="team-member">
                        <img src="/images/team/member2.jpg" alt="Член команды 2" />
                        <h3>Петр Петров</h3>
                        <p>Технический директор</p>
                    </div>
                    <div className="team-member">
                        <img src="/images/team/member3.jpg" alt="Член команды 3" />
                        <h3>Анна Сидорова</h3>
                        <p>Дизайнер</p>
                    </div>
                </div>
            </section>

            <section className="about-section">
                <h2>Наши ценности</h2>
                <div className="values-grid">
                    <div className="value-item">
                        <h3>Качество</h3>
                        <p>Мы стремимся к высокому качеству контента и сервиса</p>
                    </div>
                    <div className="value-item">
                        <h3>Сообщество</h3>
                        <p>Мы ценим наше сообщество и заботимся о нем</p>
                    </div>
                    <div className="value-item">
                        <h3>Инновации</h3>
                        <p>Мы постоянно развиваемся и внедряем новые технологии</p>
                    </div>
                </div>
            </section>

            <section className="about-section">
                <h2>Свяжитесь с нами</h2>
                <p>
                    Мы всегда открыты для обратной связи и готовы ответить на ваши вопросы:
                </p>
                <ul>
                    <li>Email: contact@example.com</li>
                    <li>Адрес: г. Москва, ул. Примерная, д. 1</li>
                    <li>Телефон: +7 (XXX) XXX-XX-XX</li>
                </ul>
            </section>
        </div>
    );
};

export default AboutPage; 