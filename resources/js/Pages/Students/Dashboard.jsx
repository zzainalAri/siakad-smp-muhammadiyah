import CardStat from '@/Components/CardStat';
import { usePage } from '@inertiajs/react';
import { IconCreditCard, IconDoor, IconNotebook } from '@tabler/icons-react';
import StudentLayout from '../../Layouts/StudentLayout';

export default function Dashboard(props) {
    const { user } = usePage().props.auth;
    return (
        <div className="flex flex-col gap-8">
            <div className="flex flex-col items-center justify-between gap-y-4 lg:flex-row">
                <div>
                    <h3 className="text-xl font-semibold leading-relaxed tracking-tight text-foreground">
                        {props.page_setting.title}
                    </h3>
                    <p className="text-sm text-muted-foreground">{props.page_setting.subtitle}</p>
                </div>
            </div>
            <div className="mb-8 grid gap-4 lg:grid-cols-3">
                <CardStat
                    data={{
                        title: 'Total Mata Pelajaran Anda',
                        icon: IconNotebook,
                        background: 'text-white bg-gradient-to-r from-green-400 via-green-500 to-green-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className="text-2xl font-bold">15</div>
                </CardStat>
                <CardStat
                    data={{
                        title: 'Kelas Anda',
                        icon: IconDoor,
                        background: 'text-white bg-gradient-to-r from-slate-400 via-slate-500 to-slate-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className="text-xl font-bold">{user.student.classroom.name}</div>
                </CardStat>
                <CardStat
                    data={{
                        title: 'Total Pembayaran',
                        icon: IconCreditCard,
                        background: 'text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className="text-2xl font-bold">{props.count.total_payments}</div>
                </CardStat>
            </div>
        </div>
    );
}

Dashboard.layout = (page) => <StudentLayout children={page} title={page.props.page_setting.title} />;
