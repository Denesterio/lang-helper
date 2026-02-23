const csrfToken = (): string => document?.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

type Method = 'POST' | 'GET' | 'DELETE' | 'PATCH';
type Params = {
    [key: string]: string|boolean|null|number;
}

const get = async (route: string, params: Params = {}): Promise<any> => {
    const url = new URL(route, window.location.origin);
    if (Object.keys(params).length > 0) {
        for (const key in params) {
            url.searchParams.append(key, String(params[key]));
        }
    }
    const response = await fetch(url);
    return response.json();
}

const getPostRequest = async (route: string, formObj: object, method: Method): Promise<any> => {
    const response = await fetch(route, {
        method: method,
        body: JSON.stringify(formObj),
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        credentials: 'include',
    });
    const json = await response.json();

    if (response.ok) {
        return json;
    }

    alert(json.message);
    throw new Error(json.message || 'Ошибка запроса');
}

const post = async (route: string, formObj: object = {}): Promise<any> => {
    return getPostRequest(route, formObj, 'POST');
}

const patch = async (route: string, formObj: object): Promise<any> => {
    return getPostRequest(route, formObj, 'PATCH');
}

const destroy = async (route: string): Promise<any> => {
    return getPostRequest(route, {}, 'DELETE');
}

export {
    get,
    post,
    patch,
    destroy,
};
