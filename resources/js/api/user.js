import { get, post } from './fetch.js';

const getUser = () => {
    return get('/api/user/get');
};

const authUser = (form) => {
    return post('/api/user/auth', form).then((data) => {
        window.location.replace('/');
        return data.user;
    });
};

const registerUser = (form) => {
    return post('/api/user/register', form).then((data) => {
        window.location.replace('/');
        return data.user;
    });
};

const logoutUser = () => {
    return post('/api/user/logout').then((data) => {
        window.location.replace('/login');
        return data.user;
    });
};

export {
    getUser,
    authUser,
    registerUser,
    logoutUser,
};
