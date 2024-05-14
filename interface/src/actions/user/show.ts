'use server'

import ApiAction from '@/functions/data/apiAction'
import { cookies } from 'next/headers'

export interface UserInterface {
  idUser: string
  firstName: string
  lastName: string
  email: string
}

export async function ShowUser() {
  try {
    const response = await ApiAction('/users/show', {
      headers: {
        'Content-Type': 'application/json',
        Authorization: 'Bearer' + cookies().get('token')?.value,
      },
      // next: {
      //   revalidate: 60 * 30,
      //   tags: ['user'],
      // },
      cache: 'no-cache',
    })
    const data = await response.json()

    return data
  } catch (err) {
    console.log(err)
  }
}
