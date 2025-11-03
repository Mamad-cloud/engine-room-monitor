import net from 'net';
import IORedis from 'ioredis';

type JSONObject = { [k: string]: any };

const TCP_PORT = Number(process.env.TCP_PORT ?? 9000);
const REDIS_URL = process.env.REDIS_URL ?? 'redis://redis:6379';

const pub = new IORedis(REDIS_URL);
const sub = new IORedis(REDIS_URL);

const clients = new Map<string, net.Socket>();

const server = net.createServer((sock) => {
  console.log('MCU connected', sock.remoteAddress, sock.remotePort);
  let buffer = '';
  let deviceId: string | null = null;

  sock.on('data', (data: Buffer) => {
    buffer += data.toString();
    let idx: number;
    while ((idx = buffer.indexOf('\n')) >= 0) {
      const line = buffer.slice(0, idx).trim();
      buffer = buffer.slice(idx + 1);
      if (!line) continue;
      try {
        const msg = JSON.parse(line) as JSONObject;
        if (!deviceId && typeof msg.device_id === 'string') {
          deviceId = msg.device_id;
          clients.set(deviceId, sock);
          console.log('Registered device', deviceId);
        }
        pub.publish('mcu.data', JSON.stringify(msg));
      } catch (err) {
        console.warn('invalid json from mcu:', line);
      }
    }
  });

  sock.on('close', () => {
    if (deviceId) clients.delete(deviceId);
    console.log('MCU disconnected', deviceId);
  });

  sock.on('error', (err) => console.error('socket error', err.message));
});

sub.subscribe('mcu.commands', (err) => {
  if (err) console.error('subscribe error', err);
});
sub.on('message', (_ch, message) => {
  try {
    const cmd = JSON.parse(message) as JSONObject;
    const id = String(cmd.device_id ?? '');
    const sock = clients.get(id);
    if (sock) {
      sock.write(JSON.stringify(cmd) + '\n');
      console.log('Sent command to', id);
    } else {
      console.warn('Device not connected:', id);
    }
  } catch (err) {
    console.error('invalid command message', err);
  }
});

server.listen(TCP_PORT, () => console.log('TCP server listening on', TCP_PORT));
