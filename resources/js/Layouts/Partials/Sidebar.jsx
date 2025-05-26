import NavLink from '@/Components/NavLink';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Link } from '@inertiajs/react';
import {
    IconBooks,
    IconBuildingSkyscraper,
    IconCalendar,
    IconCalendarTime,
    IconCircleKey,
    IconDoor,
    IconDroplets,
    IconLayout2,
    IconLogout2,
    IconMoneybag,
    IconSchool,
    IconUser,
    IconUsers,
    IconUsersGroup,
} from '@tabler/icons-react';

export default function Sidebar({ auth, url }) {
    return (
        <nav className="flex flex-1 flex-col">
            <ul role="list" className="flex flex-1 flex-col">
                <li className="-mx-6">
                    <Link
                        className="items-cemter flex gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-blue-800"
                        href={'#'}
                    >
                        <Avatar>
                            <AvatarImage src={auth.user.avatar} />
                            <AvatarFallback>{auth.user.name.substring(0, 1)}</AvatarFallback>
                        </Avatar>
                        <div className="flex flex-col text-left">
                            <span className="truncate font-bold">{auth.user.name}</span>
                            <span className="truncate">{auth.user.role_name}</span>
                        </div>
                    </Link>
                </li>

                {auth.user.roles.some((role) => ['Admin'].includes(role)) && (
                    <>
                        {/* dashboard */}
                        <NavLink
                            url={route('admin.dashboard')}
                            active={url.startsWith('/admin/dashboard')}
                            title={'Dashboard'}
                            icon={IconLayout2}
                        />

                        {/* master */}
                        <div className="px-3 py-2 text-base font-medium text-white">Master</div>
                        <NavLink
                            url={route('admin.faculties.index')}
                            active={url.startsWith('/admin/faculties')}
                            title={'Fakultas'}
                            icon={IconBuildingSkyscraper}
                        />
                        <NavLink
                            url={route('admin.departements.index')}
                            active={url.startsWith('/admin/departements')}
                            title={'Program Studi'}
                            icon={IconSchool}
                        />
                        <NavLink
                            url={route('admin.academic-years.index')}
                            active={url.startsWith('/admin/academic-years')}
                            title={'Tahun Ajaran'}
                            icon={IconCalendarTime}
                        />
                        <NavLink
                            url={route('admin.classrooms.index')}
                            active={url.startsWith('/admin/classrooms')}
                            title={'Kelas'}
                            icon={IconDoor}
                        />
                        <NavLink
                            url={route('admin.roles.index')}
                            active={url.startsWith('/admin/roles')}
                            title={'Peran'}
                            icon={IconCircleKey}
                        />

                        {/* Pengguna */}
                        <div className="px-3 py-2 text-base font-medium text-white">Pengguna</div>
                        <NavLink
                            url={route('admin.students.index')}
                            active={url.startsWith('/admin/students')}
                            title={'Mahasiswa'}
                            icon={IconUsers}
                        />
                        <NavLink
                            url={route('admin.teachers.index')}
                            active={url.startsWith('/admin/teachers')}
                            title={'Dosen'}
                            icon={IconUsersGroup}
                        />
                        <NavLink
                            url={route('admin.operators.index')}
                            active={url.startsWith('/admin/operators')}
                            title={'Operator'}
                            icon={IconUser}
                        />

                        {/* Akademik */}
                        <div className="px-3 py-2 text-base font-medium text-white">Akademik</div>
                        <NavLink
                            url={route('admin.courses.index')}
                            active={url.startsWith('/admin/courses')}
                            title={'Matakuliah'}
                            icon={IconBooks}
                        />
                        <NavLink
                            url={route('admin.schedules.index')}
                            active={url.startsWith('/admin/schedules')}
                            title={'Jadwal'}
                            icon={IconCalendar}
                        />

                        {/* Pembayaran */}
                        <div className="px-3 py-2 text-base font-medium text-white">Pembayaran</div>
                        <NavLink
                            url={route('admin.fees.index')}
                            active={url.startsWith('/admin/fees')}
                            title={'Uang Kuliah Tunggal'}
                            icon={IconMoneybag}
                        />
                        <NavLink
                            url={route('admin.fee-groups.index')}
                            active={url.startsWith('/admin/fee-groups')}
                            title={'Golongan UKT'}
                            icon={IconDroplets}
                        />
                    </>
                )}

                {auth.user.roles.some((role) => ['Teacher'].includes(role)) && (
                    <>
                        <NavLink
                            url={route('teachers.dashboard')}
                            active={url.startsWith('/teachers/dashboard')}
                            title={'Dashboard'}
                            icon={IconLayout2}
                        />
                        <div className="px-3 py-1 text-base font-medium text-white">Akademik</div>

                        <NavLink
                            url={route('teachers.courses.index')}
                            active={url.startsWith('/teachers/courses')}
                            title={'Mata Kuliah'}
                            icon={IconBooks}
                        />
                        <NavLink
                            url={route('teachers.schedules.index')}
                            active={url.startsWith('/teachers/schedules')}
                            title={'Jadwal'}
                            icon={IconCalendar}
                        />
                    </>
                )}
                {auth.user.roles.some((role) => ['Operator'].includes(role)) && (
                    <>
                        <NavLink
                            url={route('operators.dashboard')}
                            active={url.startsWith('/operators/dashboard')}
                            title={'Dashboard'}
                            icon={IconLayout2}
                        />
                        <div className="px-3 py-1 text-base font-medium text-white">Pengguna</div>

                        <NavLink
                            url={route('operators.students.index')}
                            active={url.startsWith('/operators/students')}
                            title={'Mahasiswa'}
                            icon={IconUsers}
                        />
                        <NavLink
                            url={route('operators.teachers.index')}
                            active={url.startsWith('/operators/teachers')}
                            title={'Dosen'}
                            icon={IconUsersGroup}
                        />
                        <div className="px-3 py-1 text-base font-medium text-white">Akademik</div>
                        <NavLink
                            url={route('operators.classrooms.index')}
                            active={url.startsWith('/operators/classrooms')}
                            title={'Kelas'}
                            icon={IconDoor}
                        />
                        <NavLink
                            url={route('operators.courses.index')}
                            active={url.startsWith('/operators/courses')}
                            title={'Mata Kuliah'}
                            icon={IconUsersGroup}
                        />
                        <NavLink
                            url={route('operators.schedules.index')}
                            active={url.startsWith('/operators/schedules')}
                            title={'Jadwal'}
                            icon={IconCalendar}
                        />
                    </>
                )}

                {/* Lainnya */}
                <div className="px-3 py-1 text-base font-medium text-white">Lainnya</div>
                <NavLink
                    url={route('logout')}
                    method="post"
                    as="button"
                    active={url.startsWith('/logout')}
                    title={'Logout'}
                    className="w-full"
                    icon={IconLogout2}
                />
            </ul>
        </nav>
    );
}
