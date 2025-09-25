import { jwtDecode } from "jwt-decode"
import { defineNuxtRouteMiddleware, navigateTo } from "nuxt/app"

export default defineNuxtRouteMiddleware(()=>{
    try {
        const token = localStorage.getItem('token') || ''
        const user = jwtDecode(token) ?? {}
        if(!user){
            return navigateTo('/')
        }
    } catch (error) {
        console.error(error)
        //return navigateTo('/')
    }
})
