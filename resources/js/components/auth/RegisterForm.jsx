import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const RegisterForm = ({ onSubmit }) => {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const [acceptedTerms, setAcceptedTerms] = useState(false);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!acceptedTerms) {
            alert('Пожалуйста, примите условия использования');
            return;
        }
        onSubmit(formData);
    };

    return (
        <div className="auth-form">
            <h2>Регистрация</h2>
            <form onSubmit={handleSubmit}>
                <div className="form-group">
                    <label htmlFor="name">Имя пользователя</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value={formData.name}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="password">Пароль</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        value={formData.password}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="password_confirmation">Подтверждение пароля</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        value={formData.password_confirmation}
                        onChange={handleChange}
                        required
                    />
                </div>
                <div className="form-group">
                    <label className="checkbox-label">
                        <input
                            type="checkbox"
                            checked={acceptedTerms}
                            onChange={(e) => setAcceptedTerms(e.target.checked)}
                        />
                        Я согласен с{' '}
                        <Link to="/terms">условиями использования</Link>
                    </label>
                </div>
                <button type="submit" className="btn btn-primary">
                    Зарегистрироваться
                </button>
            </form>
            <div className="auth-form__footer">
                <p>
                    Уже есть аккаунт?{' '}
                    <Link to="/login">Войти</Link>
                </p>
            </div>
        </div>
    );
};

export default RegisterForm; 