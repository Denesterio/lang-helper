import { get, post } from './fetch';
import type { User, UserAuth, UserRegister } from '../types/user.ts';

const getUser = (): Promise<User|null> => {
    return get('/api/user/get');
};

const authUser = (form: UserAuth): Promise<User|null> => {
    return post('/api/user/auth', form).then((data) => {
        window.location.replace('/');
        return data.user;
    });
};

const registerUser = (form: UserRegister): Promise<User|null> => {
    return post('/api/user/register', form).then((data) => {
        window.location.replace('/');
        return data.user;
    });
};

const logoutUser = (): Promise<User|null> => {
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
