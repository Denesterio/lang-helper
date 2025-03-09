<script setup>
	import VInputText from "../Forms/VInputText.vue";
	import VInputCheckbox from "../Forms/VInputCheckbox.vue";
	import { ref } from 'vue';
	import { registerUser } from "../../api/user";

	const name = ref(null);
	const password = ref(null);
    const passwordConfirmation = ref(null);
	const remember = ref(false);

	const emit = defineEmits([
		'update-user',
	]);

	function sendUserAuth() {
		registerUser({
				name: name.value,
				password: password.value,
				passwordConfirmation: passwordConfirmation.value,
				remember: remember.value,
			})
			.then((data) => {
				emit('update-user', data);
			});
	};
</script>

<template>
	<div>
		<v-input-text v-model="name" id="register_name" label="Имя" />
		<v-input-text v-model="password" id="register_password" label="Пароль" type="password" />
        <v-input-text
			v-model="passwordConfirmation"
			id="register_password_confirmation"
			label="Повторите пароль"
			type="password"
			class="mb-2"
		/>
		<v-input-checkbox v-model="remember" id="register_remmeber_me" label="Запомнить меня" />

		<button class="mt-2" @click.prevent="sendUserAuth">Зарегистрироваться</button>
	</div>
</template>
