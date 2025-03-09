<script setup>
	import VInputText from "../Forms/VInputText.vue";
	import VInputCheckbox from "../Forms/VInputCheckbox.vue";
	import { ref } from 'vue';
	import { authUser } from "../../api/user.js";

	const name = ref(null);
	const password = ref(null);
	const remember = ref(false);

	const emit = defineEmits([
		'update-user',
	]);

	function sendUserAuth() {
		authUser({ name: name.value, password: password.value }).then((data) => {
			emit('update-user', data);
		});
	};
</script>

<template>
	<div>
		<v-input-text v-model="name" id="auth_name" label="Имя" />
		<v-input-text v-model="password" id="auth_password" label="Пароль" type="password" class="mb-2" />
		<v-input-checkbox v-model="remember" id="auth_remember_me" label="Запомнить меня" />

		<button class="mt-2" @click.prevent="sendUserAuth">Войти</button>
	</div>
</template>
