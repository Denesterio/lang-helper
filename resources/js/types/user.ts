type User = {
    name: string,
    email: string,
    id: number,
};

type UserAuth = {
    name: string | null,
    password: string | null,
};
type UserRegister = UserAuth & {
    passwordConfirmation: string | null,
    remember: boolean,
};

export type {
    User,
    UserAuth,
    UserRegister
}
