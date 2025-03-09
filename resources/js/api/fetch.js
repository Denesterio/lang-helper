const csrfToken = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const get = async (route, params = {}) => {
    const url = new URL(route, window.location.origin);
    if (Object.keys(params).length > 0) {
        for (const key in params) {
            url.searchParams.append(key, params[key]);
        }
    }
    const response = await fetch(url);
    return response.json();
}

const getPostRequest = async (route, formObj, method) => {
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

const post = async (route, formObj) => {
    return getPostRequest(route, formObj, 'POST');
}

const patch = async (route, formObj) => {
    return getPostRequest(route, formObj, 'PATCH');
}

const destroy = async (route) => {
    return getPostRequest(route, {}, 'DELETE');
}

export {
    get,
    post,
    patch,
    destroy,
};
