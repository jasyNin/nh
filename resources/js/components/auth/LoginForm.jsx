import React, { useState } from 'react';
import { Link } from 'react-router-dom';

const LoginForm = ({ onSubmit }) => {
    const [formData, setFormData] = useState({
        email: '',
        password: '',
    });

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onSubmit(formData);
    };

    return (
        <div className="auth-form">
            <h2>Вход в аккаунт</h2>
            <form onSubmit={handleSubmit}>
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
                <button type="submit" className="btn btn-primary">
                    Войти
                </button>
            </form>
            <div className="auth-form__footer">
                <p>
                    Нет аккаунта?{' '}
                    <Link to="/register">Зарегистрироваться</Link>
                </p>
                <Link to="/terms" className="auth-form__terms">
                    Условия использования
                </Link>
            </div>
        </div>
    );
};

export default LoginForm; 