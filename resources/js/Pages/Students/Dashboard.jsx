import CardStat from '@/Components/CardStat';
import { formatToRupiah } from '@/lib/utils';
import { IconChecks, IconCreditCard, IconX } from '@tabler/icons-react';
import StudentLayout from '../../Layouts/StudentLayout';

export default function Dashboard(props) {
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
                        title: 'Kartu Rencana Studi Diterima',
                        icon: IconChecks,
                        background: 'text-white bg-gradient-to-r from-green-400 via-green-500 to-green-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className="text-2xl font-bold">{props.count.study_plans_approved}</div>
                </CardStat>
                <CardStat
                    data={{
                        title: 'Kartu Rencana Studi Ditolak',
                        icon: IconX,
                        background: 'text-white bg-gradient-to-r from-red-400 via-red-500 to-red-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className="text-2xl font-bold">{props.count.study_plans_reject}</div>
                </CardStat>
                <CardStat
                    data={{
                        title: 'Total Pembayaran',
                        icon: IconCreditCard,
                        background: 'text-white bg-gradient-to-r from-blue-400 via-blue-500 to-blue-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className="text-2xl font-bold">{formatToRupiah(props.count.total_payments)}</div>
                </CardStat>
            </div>
        </div>
    );
}

Dashboard.layout = (page) => <StudentLayout children={page} title={page.props.page_setting.title} />;
